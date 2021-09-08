<?php

namespace App\Controller\Admin;

use App\Entity\Variation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VariationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Variation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield TextEditorField::new('description');
        yield AssociationField::new('exercise');
    }
}
