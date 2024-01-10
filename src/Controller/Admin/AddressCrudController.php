<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Faker\Core\Number;

class AddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Address::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            NumberField::new('Latitude'),
            NumberField::new('Longitude'),
            TextField::new('city', 'Ville'),
            NumberField::new('postcode', 'Code postal'),
            NumberField::new('housenumber', 'N° de maison'),
            TextField::new('street', 'Rue'),
            ChoiceField::new('type', 'Type')->setChoices(['Rue'=>'street','Numéro de maison'=>'housenumber','Ville'=>'municipality', 'locality'=>'Lieu-dit']),
            AssociationField::new('user', 'Utilisateur')
                ->setCrudController(UserCrudController::class)
                ->formatValue(function ($entity) {
                    if (null == !$entity) {
                        return $entity->getEmail();
                    }
                })
                ->setFormTypeOptions([
                    'choice_label' => 'email',
                    'query_builder' => (function (EntityRepository $entityRepository) {
                        return $entityRepository->createQueryBuilder('u')
                            ->orderBy('u.email', 'ASC');
                    }),
                ]),

        ];
    }
}
