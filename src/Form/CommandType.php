<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Command;
use App\Form\TicketType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;


class CommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ticketsOrdered', CollectionType::class, array(
            'label'=> false,
            'allow_add'=>true,
            'allow_delete'=>true,
            'constraints'=>array(new Valid),
            'entry_type' =>TicketType::class,
            'entry_options' => array('label' => false),
            'by_reference' => false,
            'block_name'=> 'collection',
        ))
        ->add('DesiredDate', DateType::class, array(
            'constraints' => new GreaterThanOrEqual(array(
                'value'=>'today',
                'message'=>'Vous ne pouvez pas commander pour une date passée.',
            )),
            'format' => 'dd-MM-yyyy',
            'input'=>'datetime',
            'label' =>'Date de visite souhaitée',
            'years'=>range(date('Y'), 2028),
            'widget'=>'single_text',
            'html5'=>false,
             'attr'=>['class'=>'js-datepicker']
        ))
        ->add('DayType', ChoiceType::class, [
            'choices'=> array(
                'Journée pleine' => 1,
                'Demie-journée' => 0,
            ),
            'label'=>'Durée de la visite'
        ])
        ->add('VisitorEmail', EmailType::class, array(
            'label'=>'Votre adresse electronique (Impérative pour recevoir votre achat):'
        ))
        ->add('Save', SubmitType::class, array(
            'label'=>'Commander',
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => "App\Entity\Command"
        ));
    }
}
