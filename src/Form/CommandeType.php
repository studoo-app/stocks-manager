<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'query_builder' => function (ProduitRepository $pr): QueryBuilder {
                    return $pr->createQueryBuilder('pr')
                        ->where('pr.stock > 0')
                        ->orderBy('pr.nom', 'ASC');
                },
                'choice_label' => function (Produit $produit): string {
                    return $produit->buildLabel();
                },
            ])
            ->add('quantite',IntegerType::class)
            //->add('total',IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
