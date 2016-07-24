<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stoerung
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\StoerungRepository")
 */
class Stoerung
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Maschine", inversedBy="stoerungen")
     */
    private $maschine;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="st_start", type="datetime")
     */
    private $stStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="st_end", type="datetime")
     */
    private $stEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="st_laenge", type="datetime")
     */
    private $stLaenge;

    /**
     * @var \Boolean
     *
     * @ORM\Column(name="behoben", type="boolean")
     */
    private $behoben;

    /**
     * @var string
     *
     * @ORM\Column(name="fehlercode", type="string", length=20, nullable=true)
     */
    private $fehlercode;

    /**
     * @var string
     *
     * @ORM\Column(name="bemerkungen", type="text")
     */
    private $bemerkungen;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="massnahmen", type="text", nullable=true)
     */
    private $massnahmen;

    /**
     * @var string
     *
     * @ORM\Column(name="art", type="string", length=5)
     */
    private $art;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set stStart
     *
     * @param \DateTime $stStart
     *
     * @return Stoerung
     */
    public function setStStart($stStart)
    {
        $this->stStart = $stStart;

        return $this;
    }

    /**
     * Get stStart
     *
     * @return \DateTime
     */
    public function getStStart()
    {
        return $this->stStart;
    }

    /**
     * Set stEnd
     *
     * @param \DateTime $stEnd
     *
     * @return Stoerung
     */
    public function setStEnd($stEnd)
    {
        $this->stEnd = $stEnd;

        return $this;
    }

    /**
     * Get stEnd
     *
     * @return \DateTime
     */
    public function getStEnd()
    {
        return $this->stEnd;
    }

    /**
     * Set bemerkungen
     *
     * @param string $bemerkungen
     *
     * @return Stoerung
     */
    public function setBemerkungen($bemerkungen)
    {
        $this->bemerkungen = $bemerkungen;

        return $this;
    }

    /**
     * Get bemerkungen
     *
     * @return string
     */
    public function getBemerkungen()
    {
        return $this->bemerkungen;
    }



    /**
     * Set massnahmen
     *
     * @param string $massnahmen
     *
     * @return Stoerung
     */
    public function setMassnahmen($massnahmen)
    {
        $this->massnahmen = $massnahmen;

        return $this;
    }

    /**
     * Get massnahmen
     *
     * @return string
     */
    public function getMassnahmen()
    {
        return $this->massnahmen;
    }

    /**
     * Set maschine
     *
     * @param \AppBundle\Entity\Maschine $maschine
     *
     * @return Stoerung
     */
    public function setMaschine(\AppBundle\Entity\Maschine $maschine = null)
    {
        $this->maschine = $maschine;

        return $this;
    }

    /**
     * Get maschine
     *
     * @return \AppBundle\Entity\Maschine
     */
    public function getMaschine()
    {
        return $this->maschine;
    }

    /**
     * Set art
     *
     * @param string $art
     *
     * @return Stoerung
     */
    public function setArt($art)
    {
        $this->art = $art;

        return $this;
    }

    /**
     * Get art
     *
     * @return string
     */
    public function getArt()
    {
        return $this->art;
    }

    /**
     * Set behoben
     *
     * @param boolean $behoben
     *
     * @return Stoerung
     */
    public function setBehoben($behoben)
    {
        $this->behoben = $behoben;

        return $this;
    }

    /**
     * Get behoben
     *
     * @return boolean
     */
    public function getBehoben()
    {
        return $this->behoben;
    }

    /**
     * Set fehlercode
     *
     * @param string $fehlercode
     *
     * @return Stoerung
     */
    public function setFehlercode($fehlercode)
    {
        $this->fehlercode = $fehlercode;

        return $this;
    }

    /**
     * Get fehlercode
     *
     * @return string
     */
    public function getFehlercode()
    {
        return $this->fehlercode;
    }

     /**
     * Set stLaenge
     *
     * @param \DateTime $stLaenge
     *
     * @return Stoerung
     */
    public function setstLaenge($stLaenge)
    {

        $sql  = "date_diff(hour,stStart, stEnd)";


        $this->stLaenge = $stLaenge;

        return $this;
    }

    /**
     * Get stLaenge
     *
     * @return \DateTime
     */
    public function  getstLaenge()
     {
        return $this->stLaenge;
    }


}
