<?php

namespace App\Entity;

use App\Helpers\StringHelpers;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="text", nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="societe", type="string", length=255, nullable=true)
     */
    private $societe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessCode")
     * @ORM\JoinColumn(nullable=false, name="access_code", referencedColumnName="id")
     */
    private $accessCode;

    public function __construct()
    {
        parent::__construct();
        $this->enabled = true;
        $stringHelpers = new StringHelpers();
        $this->id      = $stringHelpers->generateUuid();
        $this->photo = "";
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        if ($photo) {
            $this->photo = $photo;
        } else {
            $this->photo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAMFBMVEX////Z2dna2trW1tbz8/P8/Pzf39/5+fnu7u7p6enh4eHw8PD29vbq6urg4ODl5eXOtJlfAAAGAUlEQVR4nO2d7bKqOgyGJaV8CMj93+2moC5FUGgT8pbd58yc2b/W8E7SJE3TerkkEolEIpFIJBKJRAIFq/0BR2Bt2eYTbVuWp9Js866/UeYwE+6fVPRdXmp/WwiTkdrumk2KlhjE3ro2XnPapqdVcQ8oM1nfRGnLtqcXGT9F5trfu5emMC/aviu866xi8tZqi6RPnMYoZObFz9W3aMTMUKP97VuwvZe+u8YrfsxpMm+BE5W2gh/4G/CB6bU1fMMOKzBcIuF6ajmEUPILo3+4v9FqK1mhDRX3B2b+L/kEYkq0myqXrSA6asGoz0mEK256XoFZVmgrmtEEJ4k5ptPW9MYYZYITxUwiVFq8spuQnJ/irMXBR3ntN2IqGImWmB30Dk48rQRM6Io3U2sreyBiwBEQI1ZGTCJIxmCuZl4hbW0jrZxAkAq8llSIsOG3MplihCBiTSuS7Z8AuGknKG+oawBS4lVUIcAmykq6qFuJ6juMkn1X8a7QqC/EXFah22AoUwkrzNRDjWi+d6jn/PCDih/ctBUKJwuAdJEUBqPupeyd4DnqkUa2LEVQKJ0P9Utv6ZpG/1z//HWpFRWIcHohvHtC6HsL9hIdhb5C4YSonvDFE6J6OnRjXqIA9PVb0XRhAKYVeadMPgCYOpFNFwDJQnj/BHH4JNqpuWqrcwgekAJ02hyiuwuAUMo8k/gOIYTSAbkDRDIIofQ/OMe/1HIL0VWl+mZkHH7+gHIAhZKhlFxdqq1Qsmgj9596F0NwEU6obxCF9WXq8ybi7VL1HaK4k6q7qfjRk3rWF+6WOszJFdLpFQ7Vt67CQrYP5VBWeECkUT7IFz/kzoxyq0b8kFu9VZNLtqFGtGsaK1zTkP4RqaxAh/b+UDzUqPeEzz9fKn2Or+6kQ76QPT8EaOvbYtsLO35AnK4J3nsCmBca8XxxZwsQBzMDthZwUzJZjeCidyz/ManptYuZGdx5UbscXYBx0NT5PECWmMM4deL+kAVagw8YK1TS3vYuw7oZBnTSIdZwKsSLMxfeaWGDMaEwh/E8H+T8fg5j1lc/NFyGMemrb3uXYdwMgxVsT9ga4Or31dZgeQ7LOQLA6PMKPIUbyqzXEjWHRNCSbYLlEhTKQOIyLNU3sAmZjIjRfVqDYfoEtJ55YEP7bgTRIf1GYPcUpUP6jaB+DdAjdKtY946Ef1YEX4R3/MtTA3CfcgvWe8gGPso88LyBgV3MvOO3F9Y/7t2M9VuKsLvCJXwulkaQCf+wPjOL0YSZCY++Wxyp8IlHZYNfzbyxP5pGFElH9ocawCPRr3jYMCkE4/wKPd6hx20DL1LtNWF02eL8Gd+jcQrw1s4ePM6DI6tLffYWUSk8/e7Jq6UYQSPxhdt+gbADGIv4jdao3x3ZgVffm2LK+V6zQxSRm/pOuMVTfHuPf0XS1A+YjYqjk+HVSXyCOBr8QdAr7dBTCnfCBqMi8NPQO0Lw1SnD02bYR2zl+CxQmELEH1l9Mj6KdWYr5kw3EWFrm47tqiXmVrFkmhCm6TdI8TyV/cFksMRY8l8jNQXQhthK3Oc2ZGDukDYk8/N5w5+tEDQ2heTzJoVbjqoyG9nb+G7eVDPk2IpMJqlwUkmdkhHLjszzK0QxWa0QV/M+E39e6JXrsbsqWxVG3j9nUHeYIfP74eCx+tz/bkcYsqwz+ecuVzGmli1YR+881HQzyFVzlZS32ryfgqeyxMGHro1A/igl3i/xh3pmb21uiotvhYKvaG2n4AJlQ8dQCHAY0jZX9cW3igk3pMjrQayEVXSt/NudHJirp7M2cg9cMUOG9vcf/zZG6G46MmjctyDtsDGKQtkTZ4rtbR0XXuLS92Bb0LGg2e8n4xf3P+1ouww3/W3ih69WFEv8XOdbWycX/sXNgzDFSu4oXXkWr3e+cZuHHGdW2ZdWj+bzfK49wQJ8w9B7T6c+jX++8BJVS8D9bSjOYM/zOdbH8qC4D+c0kZWgOyDjrhqJ/gSlLjTFVO3PEGawovYnSGNOrzBLCuMnKYyfpDB+ksL4SQrjJymMn6QwfpLC+Dm/wn/v/WdWBujuEgAAAABJRU5ErkJggg==
';
        }

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(?string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getAccessCode(): ?AccessCode
    {
        return $this->accessCode;
    }

    public function setAccessCode(?AccessCode $accessCode): self
    {
        $this->accessCode = $accessCode;

        return $this;
    }

}
