<?php

namespace App\Form;

use App\Entity\Conference;
use App\Entity\Volunteering;
use App\Repository\ConferenceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolunteeringType extends AbstractType
{
    public function __construct(
        private readonly ConferenceRepository $repository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('conference', EntityType::class, [
                'class' => Conference::class,
                'choice_label' => 'name',
            ]);

        $formModifier = function (FormInterface $form, ?Conference $conference = null) {
            if ($conference instanceof Conference) {
                $form
                    ->add('startAt', DateType::class, [
                        'widget' => 'single_text',
                        'input' => 'datetime_immutable',
                    ])
                    ->add('endAt', DateType::class, [
                        'widget' => 'single_text',
                        'input' => 'datetime_immutable',
                    ]);
                $form
                    ->get('startAt')->setData($conference->getStartAt());
                $form
                    ->get('endAt')->setData($conference->getEndAt());
            }
        };

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) use ($formModifier) {
                /** @var Volunteering $data */
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getConference());
            });
        $builder->get('conference')
            ->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event) use ($formModifier) {
                $data = $event->getForm()->getData();
                $form = $event->getForm()->getParent();

                $formModifier($form, $data);

                if ($data instanceof Conference && $form->has('startAt') && $form->has('endAt')) {
                    $form
                        ->get('startAt')->setData($data->getStartAt());
                    $form
                        ->get('endAt')->setData($data->getEndAt());

                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Volunteering::class,
                'conference' => null,
            ])
            ->setAllowedTypes('conference', ['null', Conference::class]);
    }
}
