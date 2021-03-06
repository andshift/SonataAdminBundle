<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ChoiceType.
 *
 * @author  Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ChoiceType extends AbstractType
{
    const TYPE_CONTAINS = 1;

    const TYPE_NOT_CONTAINS = 2;

    const TYPE_EQUAL = 3;

    /**
     * @deprecated since 3.5, to be removed with 4.0
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     *
     * @todo Remove when dropping Symfony <2.8 support
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sonata_type_filter_choice';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            self::TYPE_CONTAINS => 'label_type_contains',
            self::TYPE_NOT_CONTAINS => 'label_type_not_contains',
            self::TYPE_EQUAL => 'label_type_equals',
        );

        if (!method_exists('Symfony\Component\Form\FormTypeInterface', 'setDefaultOptions')) {
            $choices = array_flip($choices);
        }

        // NEXT_MAJOR: Remove this hack, when dropping support for symfony <2.7
        if (!method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            foreach ($choices as $key => $value) {
                $choices[$key] = $this->translator->trans($value, array(), 'SonataAdminBundle');
            }
        }

        $operatorChoices = array();
        if ($options['operator_type'] !== 'hidden') {
            $operatorChoices['choices'] = $choices;

            // NEXT_MAJOR: Remove this hack, when dropping support for symfony <2.7
            if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
                $operatorChoices['choice_translation_domain'] = 'SonataAdminBundle';
            }
        }

        $builder
            ->add('type', $options['operator_type'], array_merge(array('required' => false), $options['operator_options'], $operatorChoices))
            ->add('value', $options['field_type'], array_merge(array('required' => false), $options['field_options']))
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @todo Remove it when bumping requirements to SF 2.7+
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'field_type' => 'choice',
            'field_options' => array(),
            'operator_type' => 'choice',
            'operator_options' => array(),
        ));
    }
}
