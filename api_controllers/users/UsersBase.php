<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/Constantes.php';

class UsersBase implements JsonSerializable
{
    public function __construct()
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
    public function __construct1(array $data)
    {
        $has = get_object_vars($this);
        foreach ($has as $name => $oldValue) {
            $this->$name = isset($data[$name]) ? $data[$name] : NULL;
        }
    }

    /**
     * Pour la serialisation de l'objet
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @var string
     */
    private $identifiant;

    /**
     * @var string
     */
    private $pseudo;

    /**
     * @var string
     */
    private $avatar;

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

}
