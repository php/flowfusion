--TEST--
Translated seed (inferredbugs-java: cassandra-lucene-index/5)
--FILE--
<?php

class Schema {
    private $defaultAnalyzer;
    private $analyzers = [];
    private $fields = [];

    public function defaultAnalyzer($analyzer) {
        $this->defaultAnalyzer = $analyzer;
        return $this;
    }

    public function analyzer($name, $type) {
        $this->analyzers[$name] = $type;
        return $this;
    }

    public function field($name, $type, $options = []) {
        $this->fields[$name] = array_merge(['type' => $type], $options);
        return $this;
    }

    public function build() {
        return $this;
    }

    public function toJson() {
        return json_encode([
            'default_analyzer' => $this->defaultAnalyzer,
            'analyzers' => $this->analyzers,
            'fields' => $this->fields
        ]);
    }
}

$json = (new Schema())
    ->defaultAnalyzer("custom")
    ->analyzer("custom", ["type" => "classpath", "class" => "org.apache.lucene.analysis.en.EnglishAnalyzer"])
    ->analyzer("snowball", ["type" => "snowball", "language" => "English", "stopwords" => "the,at"])
    ->field("big_int", "bigint", ["digits" => 10])
    ->field("big_dec", "bigdec", ["indexed" => false, "sorted" => true])
    ->field("bitemporal", "bitemporal", ["vt_from" => "vtFrom", "vt_to" => "vtTo", "tt_from" => "ttFrom", "tt_to" => "ttTo"])
    ->field("blob", "bytes")
    ->field("bool", "boolean")
    ->field("date", "date")
    ->field("date_range", "date_range", ["from" => "from", "to" => "to"])
    ->field("double", "double")
    ->field("float", "float")
    ->field("geo", "geo_point", ["latitude" => "lat", "longitude" => "lon"])
    ->field("inet", "inet")
    ->field("int", "integer", ["boost" => 0.3])
    ->field("long", "long")
    ->field("string", "string")
    ->field("text", "text")
    ->field("uuid", "uuid")
    ->toJson();

echo $json;
--EXPECT--
