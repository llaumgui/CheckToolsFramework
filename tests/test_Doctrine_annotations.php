// src/Acme/StoreBundle/Entity/Product.php
/*
 *	According to the symfony2 doc (http://symfony.com/doc/current/book/doctrine.html)
 *  The namespace must match to : namespace [something]\[something].Bundle\Entity 
 *  Entity need to be the name of the object repository for mapping to db
 */
namespace Acme\StoreBundle\Entityz;

/*
 * You need to use Doctrine ORM Mapping for mapping your object to the db
 * According to the online documentation of Symfony2
 * it's something like : 
 *	use Doctrine\ORM\Mapping as ORM
 */
use Doctrine\ORM\Mappi;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $price;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;
}