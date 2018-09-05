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
            ))
            ->add('VisitorSurName', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('VisitorCountry', CountryType::class)
            ->add('DesiredDate', DateType::class, array(
                'constraints' => new GreaterThanOrEqual(array(
                    'value'=>'today',
                    'message'=>'Vous ne pouvez pas commander pour une date passée.',
                    )),
                'format' => 'ddMMMMyyyy',
            ))
            ->add('DayType', ChoiceType::class, [
                'choices'=> array(
                    'Journée pleine' => 1,
                    'Demie-journée' => 0,
                )
            ])
            ->add('VisitorDoB', BirthdayType::class, array(
                'format'=> 'ddMMMMyyyy',
            ))
            ->add('ReducedPrice', CheckboxType::class, array(
                'required'=>false,
            ))
            ->add('Save', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>"App\Entity\Tickets"
        ));
    }
}
?>