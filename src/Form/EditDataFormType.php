<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Resume;
use App\Entity\Vacancy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

/**
 * Форма добавления нового или изменения существующего резюме
 */
class EditDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sendingDatetimeYears = array();
        for($year = 2000; $year <= 2021; $year++) $sendingDatetimeYears[] = $year;
        
        $invalid_message = 'Это значение недопустимо!';

        $builder
            ->add('fullName', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'ФИО должно быть введёно!',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'ФИО не может быть больше {{ limit }} символов!',
                    ]),
                ],
            ])
            ->add('about'           , TextareaType::class, [
                'required' => false,
            ])
            ->add('workExperience', IntegerType::class, [
                'constraints' => [
                    new PositiveOrZero([
                        'message' => 'Опыт работы не может быть меньше нуля!',
                    ])
                ],
                'invalid_message' => $invalid_message,
            ])
            ->add('desiredSalary', NumberType::class, [
                'constraints' => [
                    new PositiveOrZero([
                        'message' => 'Желаемая заработная плата не может быть меньше нуля!',
                    ]),
                ],
                'invalid_message' => $invalid_message,
            ])
            ->add('birthDate', BirthdayType::class, [
                'widget' => 'single_text',
            ])
            ->add('sendingDatetime', DateTimeType::class, [
                'with_seconds' => true,
                'years' => $sendingDatetimeYears,
                'widget' => 'single_text',
            ])
            ->add('deleteAvatar', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('avatar', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new ConstraintsFile([
                        'maxSize' => '5120k',
                        'mimeTypes' => [
                            'image/bmp',
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Разрешённые форматы: .bmp, .gif, .jpeg, .jpg, .png.',
                        'maxSizeMessage' => 'Максимальный размер файла: 5 мб!'
                    ])
                ],
            ])
            ->add('deleteFile', CheckboxType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new ConstraintsFile([
                        'maxSize' => '20480k',
                        'maxSizeMessage' => 'Максимальный размер файла: 20 мб!'
                    ])
                ],
            ])
            ->add('cityToWorkIn', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('desiredVacancy', EntityType::class, [
                'class' => Vacancy::class,
                'choice_label' => function ($choice, $key, $value) {
                    return $choice->getNameWithTabs();
                },
                'choice_value' => 'id',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
        ]);
    }
}
