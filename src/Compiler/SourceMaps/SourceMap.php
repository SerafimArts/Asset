<?php
namespace Serafim\Asset\Compiler\SourceMaps;

use Exception;

/**
 * Class SourceMap
 * @package Serafim\Asset\Compiler\SourceMaps
 */
class SourceMap
{
    /**
     * @var array
     */
    protected $map = [];

    /**
     * @var string
     */
    protected $sourceCode = '';

    /**
     * @var
     */
    protected $mapping;

    protected $base64vlq;

    /**
     * @param $sourceCode      source code
     * @param array $map default map
     * @throws Exception
     */
    public function __construct($sourceCode, array $map = [])
    {
        $this->base64vlq = new Base64VLQ;
        $this->map = (object)array_merge([
            'version'  => 3,
            'mappings' => '',
            'sources'  => [],
            'names'    => [],
        ], $map);


        if ($this->map->version != 3) {
            throw new Exception('Unsupported source map version');
        }

        $this->sourceCode = $sourceCode;
    }

    /**
     * @param $file
     */
    public function setFile($file)
    {
        $this->map->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->map->file;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->map->version;
    }

    /**
     * @param $sourceRoot
     */
    public function setSourceRoot($sourceRoot)
    {
        $this->map->sourceRoot = $sourceRoot;
    }

    /**
     * @return mixed
     */
    public function getSourceRoot()
    {
        return $this->map->sourceRoot;
    }


    /**
     * Adds a mapping
     *
     * @param array $generated The [line, column] in generated file
     * @param array $original The [line, column] in original file
     * @param $source           The original source file
     * @param null $name Token name
     * @throws Exception
     */
    public function add(array $generated, array $original, $source, $name = null)
    {
        if (!isset($this->mapping)) {
            $this->getMappings();
        }

        if (count($generated) != 2 || count($original) != 2) {
            throw new Exception('$generated and $griginal must exists two values ([int $line, int $column])');
        }

        $this->mapping[] = [
            'generatedLine'   => $generated[0],
            'generatedColumn' => $generated[1],
            'originalLine'    => $original[0],
            'originalColumn'  => $original[1],
            'originalSource'  => $source,
            'originalName'    => $name,
        ];
    }


    /**
     * Generates the mappings string
     *
     * Parts based on https://github.com/oyejorge/less.php/blob/master/lib/Less/SourceMap/Generator.php
     * Apache License Version 2.0
     *
     *
     * @return array|object
     */
    private function generate()
    {
        if (!isset($this->mapping) && $this->map->mappings) {
            // up to date, nothing to do
            return;
        }
        if (!count($this->mapping)) {
            return '';
        }

        $this->map->sources = [];
        foreach ($this->mapping as $m) {
            if ($m['originalSource'] && !in_array($m['originalSource'], $this->map->sources)) {
                $this->map->sources[] = $m['originalSource'];
            }
        }

        $this->map->names = [];
        foreach ($this->mapping as $m) {
            if ($m['originalName'] && !in_array($m['originalName'], $this->map->names)) {
                $this->map->names[] = $m['originalName'];
            }
        }

        // group mappings by generated line number.
        $groupedMap = $groupedMapEncoded = [];
        foreach ($this->mapping as $m) {
            $groupedMap[$m['generatedLine']][] = $m;
        }
        ksort($groupedMap);

        $lastGeneratedLine = $lastOriginalSourceIndex = $lastOriginalNameIndex = $lastOriginalLine = $lastOriginalColumn = 0;

        foreach ($groupedMap as $lineNumber => $lineMap) {
            while (++$lastGeneratedLine < $lineNumber) {
                $groupedMapEncoded[] = ';';
            }

            $lineMapEncoded = [];
            $lastGeneratedColumn = 0;

            foreach ($lineMap as $m) {
                $mapEncoded = $this->base64vlq->encode($m['generatedColumn'] - $lastGeneratedColumn);
                $lastGeneratedColumn = $m['generatedColumn'];

                // find the index
                if ($m['originalSource']) {
                    $index = array_search($m['originalSource'], $this->map->sources);
                    $mapEncoded .= $this->base64vlq->encode($index - $lastOriginalSourceIndex);
                    $lastOriginalSourceIndex = $index;

                    // lines are stored 0-based in SourceMap spec version 3
                    $mapEncoded .= $this->base64vlq->encode($m['originalLine'] - 1 - $lastOriginalLine);
                    $lastOriginalLine = $m['originalLine'] - 1;

                    $mapEncoded .= $this->base64vlq->encode($m['originalColumn'] - $lastOriginalColumn);
                    $lastOriginalColumn = $m['originalColumn'];

                    if ($m['originalName']) {
                        $index = array_search($m['originalName'], $this->map->names);
                        $mapEncoded .= $this->base64vlq->encode($index - $lastOriginalNameIndex);
                        $lastOriginalNameIndex = $index;
                    }
                }

                $lineMapEncoded[] = $mapEncoded;
            }

            $groupedMapEncoded[] = implode(',', $lineMapEncoded) . ';';
        }

        $this->map->mappings = rtrim(implode($groupedMapEncoded), ';');

        return $this->map;
    }

    /**
     * Performant Source Map aware string replace
     *
     * @param $string
     * @param $replace
     * @throws Exception
     */
    public function replace($string, $replace)
    {
        if (strpos("\n", $string)) {
            throw new Exception('string must not contain \n');
        }
        if (strpos("\n", $replace)) {
            throw new Exception('replace must not contain \n');
        }

        $adjustOffsets = [];
        $pos = 0;
        $str = $this->sourceCode;
        $offset = 0;
        while (($pos = strpos($str, $string, $pos)) !== false) {
            $this->sourceCode = substr($this->sourceCode, 0,
                    $pos + $offset) . $replace . substr($this->sourceCode, $pos + $offset + strlen($string));

            $offset += strlen($replace) - strlen($string);
            $line = substr_count(substr($str, 0, $pos), "\n") + 1;
            $column = $pos - strrpos(
                    substr($str, 0, $pos),
                    "\n"
                ); //strrpos can return false for first line which will subtract 0 (=false)
            $adjustOffsets[$line][] = [
                'column'         => $column,
                'absoluteOffset' => $offset,
                'offset'         => strlen($replace) - strlen($string)
            ];
            $pos = $pos + strlen($string);
        }
        $generatedLine = 1;
        $previousGeneratedColumn = 0;
        $newPreviousGeneratedColumn = 0;

        $str = $this->map->mappings;

        $newMappings = '';
        while (strlen($str) > 0) {
            if ($str[0] === ';') {
                $generatedLine++;
                $newMappings .= $str[0];
                $str = substr($str, 1);
                $previousGeneratedColumn = 0;
                $newPreviousGeneratedColumn = 0;
            } else {
                if ($str[0] === ',') {
                    $newMappings .= $str[0];
                    $str = substr($str, 1);
                } else {
                    // Generated column.
                    $value = $this->base64vlq->decode($str);
                    $generatedColumn = $previousGeneratedColumn + $value;
                    $previousGeneratedColumn = $generatedColumn;
                    $newGeneratedColumn = $newPreviousGeneratedColumn + $value;

                    $offset = 0;
                    if (isset($adjustOffsets[$generatedLine])) {
                        foreach ($adjustOffsets[$generatedLine] as $col) {
                            if ($generatedColumn > $col['column']) {
                                $offset += $col['offset'];
                            }
                        }
                    }
                    $generatedColumn += $offset;
                    $newMappings .= $this->base64vlq->encode($generatedColumn - $newPreviousGeneratedColumn);
                    $newPreviousGeneratedColumn = $generatedColumn;

                    //read rest of block as it is
                    while (strlen($str) > 0 && !($str[0] == ',' || $str[0] == ';')) {
                        $newMappings .= $str[0];
                        $str = substr($str, 1);
                    }
                }
            }
        }
        $this->map->mappings = $newMappings;
        unset($this->mapping); //force re-parse
    }

    /**
     * Return all mappings
     *
     * @return array with assoc array containing: generatedLine, generatedColumn, originalSource, originalLine, originalColumn, name
     */
    public function getMappings()
    {
        if (isset($this->mapping)) {
            return $this->mapping;
        }

        $this->mapping = [];

        $generatedLine = 1;
        $previousGeneratedColumn = 0;
        $previousOriginalLine = 0;
        $previousOriginalColumn = 0;
        $previousSource = 0;
        $previousName = 0;

        $str = $this->map->mappings;

        while (strlen($str) > 0) {
            if ($str[0] === ';') {
                $generatedLine++;
                $str = substr($str, 1);
                $previousGeneratedColumn = 0;
            } else {
                if ($str[0] === ',') {
                    $str = substr($str, 1);
                } else {
                    $mapping = [];
                    $mapping['generatedLine'] = $generatedLine;

                    // Generated column.
                    $value = $this->base64vlq->decode($str);
                    $mapping['generatedColumn'] = $previousGeneratedColumn + $value;
                    $previousGeneratedColumn = $mapping['generatedColumn'];

                    if (strlen($str) > 0 && !($str[0] == ',' || $str[0] == ';')) {
                        // Original source.
                        $value = $this->base64vlq->decode($str);
                        $mapping['originalSource'] = (isset($this->map->sourceRoot) ? $this->map->sourceRoot . '/' : '')
                            . $this->map->sources[$previousSource + $value];
                        $previousSource += $value;
                        if (strlen($str) === 0 || ($str[0] == ',' || $str[0] == ';')) {
                            throw new Exception('Found a source, but no line and column');
                        }

                        // Original line.
                        $value = $this->base64vlq->decode($str);
                        $mapping['originalLine'] = $previousOriginalLine + $value;
                        $previousOriginalLine = $mapping['originalLine'];
                        // Lines are stored 0-based
                        $mapping['originalLine'] += 1;
                        if (strlen($str) === 0 || ($str[0] == ',' || $str[0] == ';')) {
                            throw new Exception('Found a source and line, but no column');
                        }

                        // Original column.
                        $value = $this->base64vlq->decode($str);
                        $mapping['originalColumn'] = $previousOriginalColumn + $value;
                        $previousOriginalColumn = $mapping['originalColumn'];

                        if (strlen($str) > 0 && !($str[0] == ',' || $str[0] == ';')) {
                            // Original name.
                            $value = $this->base64vlq->decode($str);
                            $mapping['name'] = $this->map->names[$previousName + $value];
                            $previousName += $value;
                        }
                    }
                    $this->mapping[] = $mapping;
                }
            }
        }

        return $this->mapping;
    }


    /**
     * Concat sourcemaps and keep mappings intact
     * This is implemented very efficent by avoiding to parse the whole mappings string.
     *
     * @param SourceMap $other
     * @throws Exception
     */
    public function concat(SourceMap $other)
    {
        if (strlen($this->sourceCode) > 0 && substr($this->sourceCode, -1) != "\n") {
            $this->sourceCode .= "\n";
            $this->map->mappings .= ';';
        }
        $this->sourceCode .= $other->sourceCode;

        $data = $other->getMapContentsData();

        $previousFileLast = (object)[
            'source'         => 0,
            'originalLine'   => 0,
            'originalColumn' => 0,
            'name'           => 0,
        ];

        if (!$data->mappings) {
            $data->mappings = str_repeat(';', substr_count($other->sourceCode, "\n"));
        }

        $previousFileSourcesCount = count($this->map->sources);
        $previousFileNamesCount = count($this->map->names);
        if ($previousFileLast->source > $previousFileSourcesCount) {
            if ($previousFileSourcesCount != 0 && $previousFileLast->source != 0) {
                throw new Exception("Invalid last source, must not be higher than sourceCode");
            }
        }

        if ($previousFileLast->name > $previousFileNamesCount) {
            if ($previousFileNamesCount != 0 && $previousFileLast->name != 0) {
                throw new Exception("Invalid last name, must not be higher than names");
            }
        }

        $otherMappings = '';

        if ($data->sourceCode) {
            foreach ($data->sourceCode as $s) {
                $this->map->sources[] = $s;
            }
        }
        if ($data->names) {
            foreach ($data->names as $n) {
                $this->map->names[] = $n;
            }
        }

        $otherMappings = $data->mappings;

        $str = '';

        while (strlen($otherMappings) > 0 && $otherMappings[0] === ';') {
            $str .= $otherMappings[0];
            $otherMappings = substr($otherMappings, 1);
        }
        if (strlen($otherMappings) > 0) {

            // Generated column.
            $str .= $this->base64vlq->encode($this->base64vlq->decode($otherMappings));
            if (strlen($otherMappings) > 0 && !($otherMappings[0] == ',' || $otherMappings[0] == ';')) {

                // Original source.
                $value = $this->base64vlq->decode($otherMappings);
                if ($previousFileSourcesCount) {
                    $absoluteValue = $value + $previousFileSourcesCount;
                    $value = $absoluteValue - $previousFileLast->source;
                }
                $str .= $this->base64vlq->encode($value);

                // Original line.
                $str .= $this->base64vlq->encode($this->base64vlq->decode($otherMappings) - $previousFileLast->originalLine);

                // Original column.
                $str .= $this->base64vlq->encode($this->base64vlq->decode($otherMappings) - $previousFileLast->originalColumn);

                // Original name.
                if (strlen($otherMappings) > 0 && !($otherMappings[0] == ',' || $otherMappings[0] == ';')) {
                    $value = $this->base64vlq->decode($otherMappings);
                    if ($previousFileNamesCount) {
                        $absoluteValue = $value + $previousFileNamesCount;
                        $value = $absoluteValue - $previousFileLast->name;
                    }
                    $str .= $this->base64vlq->encode($value);
                } else {
                    if (!count($data->names)) {
                        //file doesn't have names at all, we don't have to adjust that offset
                    } else {
                        //loop thru mappings until we find a block with name
                        while (strlen($otherMappings) > 0) {
                            if ($otherMappings[0] === ';') {
                                $str .= $otherMappings[0];
                                $otherMappings = substr($otherMappings, 1);
                            } else {
                                if ($otherMappings[0] === ',') {
                                    $str .= $otherMappings[0];
                                    $otherMappings = substr($otherMappings, 1);
                                } else {
                                    // Generated column.
                                    $str .= $this->base64vlq->encode($this->base64vlq->decode($otherMappings));

                                    if (strlen($otherMappings) > 0 && !($otherMappings[0] == ',' || $otherMappings[0] == ';')) {
                                        // Original source.
                                        $str .= $this->base64vlq->encode($this->base64vlq->decode($otherMappings));

                                        // Original line.
                                        $str .= $this->base64vlq->encode($this->base64vlq->decode($otherMappings));

                                        // Original column.
                                        $str .= $this->base64vlq->encode($this->base64vlq->decode($otherMappings));

                                        if (strlen($otherMappings) > 0 && !($otherMappings[0] == ',' || $otherMappings[0] == ';')) {
                                            // Original name.
                                            $value = $this->base64vlq->decode($otherMappings);
                                            if ($previousFileNamesCount) {
                                                $absoluteValue = $value + $previousFileNamesCount;
                                                $value = $absoluteValue - $previousFileLast->name;
                                            }
                                            $str .= $this->base64vlq->encode($value);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

        }

        $this->map->mappings .= $str . $otherMappings;
    }


    /**
     * Returns the contents of the source map as object (that can be json_encoded)
     *
     * @return stdObject
     */
    public function toArray($includeLastExtension = true)
    {
        $this->generate();

        return (array)$this->map;
    }

    /**
     * @param bool $includeLastExtension
     * @return string
     */
    public function toJson($includeLastExtension = true)
    {
        return json_encode($this->toArray($includeLastExtension));
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->toJson();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->toString();
    }
}
