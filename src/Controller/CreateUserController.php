<?php


namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserController extends AbstractController
{
    #[Route('/createuser', name: 'app_create_user')]
    //Attention aux paramètres passés à cette fonction
        //ManagerRegistry $doctrine: il nous sert à accéder aux méthodes de l'ORM Doctrine
        //pour écrire (ou faire persister) et lire les objets dans la base de données
        //UserPasswordHasherInterface $passwordHasher: il nous sert à accéder à la méthode de hachage d'un mot de passe
        //NB: penser à bien déclarer les namespaces ci-dessus correspondant aux classes utilisées, avec use .....
    public function index(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $newUser = new User(); //j'instancie un objet de la classe User
        $newUser->setLogin('titi');
        $newUser->setNom('titi');
        $newUser->setPrenom('titi');
        $newUser->setCp('74000');
        $newUser->setVille('Annecy');
        $newUser->setDateEmbauche(new \DateTime('2022-09-13'));

        $plaintextpassword = 'toto'; //on stocke le mot de passe en clair dans une variable
        $hashedpassword = $passwordHasher->hashPassword($newUser, $plaintextpassword); //on hache le mot de passe
        //grace à la méthode hashPassword()
        $newUser->setPassword($hashedpassword); //j'affecte le mot de passe haché à l'attribut Password de mon objet

        //Faire persister l'objet créé = l'enregistrer en base de données gràce à l'ORM Doctrine
        $doctrine->getManager()->persist($newUser); //je fais persister l'objet $newUser en base de données
        $doctrine->getManager()->flush(); //flush est à appeler après avoir fait un persist

        //enfin, je génère la page web à partir du template index.html.twig situé dans /templates/create_user
        //et je passe à cette page la variable 'userlogin' que je vais pouvoir réutiliser dans le template Twig
        //en la glissant dans la page html.twig avec {{userlogin}}
        return $this->render('create_user/index.html.twig', [
            'userlogin' => $newUser->getLogin(),
        ]);
    }
}
