<?php

// l'héritage de la classe JsonSerializable est
// pour la serialisation de l'objet en json object
class CollectorBase implements JsonSerializable
{
    protected $id;
    protected $date_add;
    protected $author;
    protected $speaker;

    protected $type_collector;
    protected $collector;
    protected $context;

    protected $date_collector;

    /**
     * @var VotesCollector[]
     */
    protected $votesCollector;

    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this, $f = '__construct' . $i)) {
            call_user_func_array(array($this, $f), $a);
        }
    }

    /**
     * fill an object in PHP from an Array
     * @param array $data
     */
    function __construct1(array $data)
    {
        $has = get_object_vars($this);
        foreach ($has as $name => $oldValue) {
            $this->$name = isset($data[$name]) ? $data[$name] : NULL;
        }
    }

    // Pour la serialisation de l'objet
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


    // getters et setters pour l'objet Collector
    // id
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return VotesCollector[]
     */
    public function getVotesCollector(): array
    {
        return $this->votesCollector;
    }

    /**
     * @param VotesCollector[] $votesCollector
     */
    public function setVotesCollector(array $votesCollector)
    {
        $this->votesCollector = $votesCollector;
    }

    // Date ajout
    public function setDate_add($date_add)
    {
        $this->date_add = $date_add;
    }

    public function getDate_add()
    {
        return $this->date_add;
    }

    // Auteur
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    // Personne
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
    }

    public function getSpeaker()
    {
        return $this->speaker;
    }

    // Date Collector
    public function setDate_collector($date_collector)
    {
        $this->date_collector = $date_collector;
    }

    public function getDate_collector()
    {
        return $this->date_collector;
    }

    // Type Collector
    public function setType_collector($type_collector)
    {
        $this->type_collector = $type_collector;
    }

    public function getType_collector()
    {
        return $this->type_collector;
    }

    // Phrase collector
    public function setCollector($collector)
    {
        $this->collector = $collector;
    }

    public function getCollector()
    {
        return $this->collector;
    }

    // Contexte collector
    public function setContext($context)
    {
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }
}
