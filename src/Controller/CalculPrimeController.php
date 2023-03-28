<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\LigneFraisForfait;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculPrimeController extends AbstractController
{
    #[Route('/calcul/prime', name: 'app_calcul_prime')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(FicheFrais::class);
        $FichesFrais = $repository->findAll();
        $fichefrais2021 =[];
        foreach ($FichesFrais as $uneficheFrais){
            if(str_contains($uneficheFrais->getMois(),'2021')){
                $fichefrais2021 = $FichesFrais;
            }
        }
        $total = 0;
        foreach ($fichefrais2021 as $unmontant){
            $total += $unmontant->getMontantValide();
        }

        $repository = $doctrine->getRepository(User::class);
        $nb = $repository->findAll();
        $nbvisiteur = count($nb);
        $parvisiteur = $total/$nbvisiteur;

        $total1 = 0;
        foreach ($FichesFrais as $uneFicheFrais){
            $total1 += $uneficheFrais->getMontantLigneFrais();
        }




        return $this->render('calcul_prime/index.html.twig', [
            'calculPrime' => $total * 0.095,
            'parvisiteur' => $parvisiteur * 0.095,
            'montantTotal' => $total1,
        ]);
    }


}
