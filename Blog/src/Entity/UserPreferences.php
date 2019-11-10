<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPreferencesRepository")
 */
class UserPreferences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=8)
     */
    private $locale;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Get the value of locale
     * @return mixed
     */ 
    public function getLocale()
    {
        return $this->locale;
    }
    /**
     * Set the value of locale
     *
     * @param mixed $locale
     */ 
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}