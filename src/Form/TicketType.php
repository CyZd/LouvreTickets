<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('VisitorName', TextType::class, array(
                'constraints' => new NotBlank(),
                'label' => 'Prénom'
            ))
            ->add('VisitorSurName', TextType::class, array(
                'constraints' => array(new NotBlank()),
                'label'=> 'Nom'
            ))
            ->add('VisitorCountry', CountryType::class, array(
                'label'=> 'Pays d\'origine'
            ))
            ->add('VisitorDoB', BirthdayType::class, array(
                'format'=> 'dd-MM-yyyy',
                'label'=> 'Date de naissance',
                'help'=>'Connaitre votre âge nous permet d\'ajuster nos tarifs.',
                'widget'=>'single_text',
                'html5'=>false,
                'attr'=>['class'=>'js-datepicker-birth']
            ))
            ->add('ReducedPrice', CheckboxType::class, array(
                'required'=>false,
                'label'=>'Je dispose d\'un tarif réduit',
                'help'=>'La réduction s\'applique sur présentation en caisse d\'un justificatif.',
                'translation_domain'=> null
            ));
        // ->add('Save', SubmitType::class, array(
            //     'label'=>'Commander'
            // ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>"App\Entity\Ticket"
        ));
    }
}
