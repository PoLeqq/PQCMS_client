<?php

/**
 * Reprezentuje wersję dodatku
 */
class AddonVersion
{
    /**
     * @var string typ wersji (np. alpha, beta, release-candidate, release, patch)
     */
    protected string $type;
    /**
     * @var int numer "major"
     */
    protected int $major;
    /**
     * @var int numer "minor"
     */
    protected int $minor;
    /**
     * @var int numer "patch"
     */
    protected int $patch;
    /**
     * @var string data wersji
     */

    protected string $date;

    /**
     * @param string $type
     * @param int $major
     * @param int $minor
     * @param int $patch
     * @param string $date
     */
    public function __construct(string $type, int $major, int $minor, int $patch, string $date)
    {
        $this->type = $type;
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
        $this->date = $date;
    }

    /**
     * @return string typ wersji
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int numer "major"
     */
    public function getMajor(): int
    {
        return $this->major;
    }

    /**
     * @return int numer "minor"
     */
    public function getMinor(): int
    {
        return $this->minor;
    }

    /**
     * @return int numer "patch"
     */
    public function getPatch(): int
    {
        return $this->patch;
    }

    /**
     * @return string data wersji
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param bool $date czy zwracana wersja zawiera datę
     * @return string Zwraca pełne dane wersji w formie stringu ([typ_wersji]-[major].[minor].[patch] ([data]))
     */
    public function getFullVersion(bool $date): string
    {
        $type = $this->type;
        $major = $this->major;
        $minor = $this->minor;
        $patch = $this->patch;
        $verDate = $this->date;

        $strDate = "$type-$major.$minor.$patch";
        if($date)
            $strDate .= " ($verDate)";

        return $strDate;
    }

    public static function serialize(AddonVersion $version): array
    {
        return [
            "type" => $version->getType(),
            "major" => $version->getMajor(),
            "minor" => $version->getMinor(),
            "patch" => $version->getPatch(),
            "date" => $version->getDate(),
        ];
    }

    public static function unserialize(array $data): AddonVersion
    {
        return new AddonVersion($data["type"],$data["major"],$data["minor"],$data["patch"],$data["date"]);
    }
}