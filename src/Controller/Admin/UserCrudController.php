<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $IuserPasswordHasher;

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('lastname'),
            TextField::new('firstname'),
            ArrayField::new('roles')
                ->formatValue(function ($value) {
                    if ('' != $value[0]) {
                        if ('ROLE_ADMIN' == $value[0]) {
                            return '<span class="material-symbols-outlined">manage_accounts</span>';
                        }
                        if ('ROLE_USER' == $value[0]) {
                            return '<span class="material-symbols-outlined">person</span>';
                        }
                    } else {
                        return '';
                    }
                }),
            TextField::new('password')
                ->hideOnIndex()
                ->setFormType(PasswordType::class)
                ->setCustomOptions([
                    'required' => 'False',
                    'empty_data' => '',
                    'attr' => ['autocomplete' => 'new-password'],
                ]),
        ];
    }

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->IuserPasswordHasher = $passwordHasher;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $password = $this->getContext()->getRequest()->get('User')['password'] ?? '';
        if ('' !== $password) {
            $entityInstance->setPassword($this->IuserPasswordHasher->hashPassword($entityInstance, $password));
            parent::updateEntity($entityManager, $entityInstance);
        }
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $password = $this->getContext()->getRequest()->get('User')['password'] ?? '';
        if ('' !== $password) {
            $entityInstance->setPassword($this->IuserPasswordHasher->hashPassword($entityInstance, $password));
            parent::persistEntity($entityManager, $entityInstance);
        }
    }
}
