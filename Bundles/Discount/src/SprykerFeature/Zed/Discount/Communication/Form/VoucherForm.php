<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class VoucherForm extends AbstractForm
{
    /**
     * Prepares form
     *
     * @return $this
     */
    protected function buildFormFields()
    {
        $this
//            ->addText('poll')
            ->addChoice('poll', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getPolls(),
            ])
            ->addText('name')
            ->addChoice('validity', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getValidity(),
            ])
//            ->addText('combinate')
            ->addCheckbox('combine', [
                'label' => 'Combinable',
            ])
        ;
    }

    private function getValidity()
    {
        $vouchers = [];

        for ($i=3; $i<=20; $i++) {
            $vouchers[$i] = $i . ' Years';
        }

        return $vouchers;
    }

    private function getPolls()
    {
        return [
            'alfa',
            'beta',
        ];
    }

    /**
     * Set the values for fields
     *
     * @return $this
     */
    protected function populateFormFields()
    {
        // @TODO: Implement populateFormFields() method.
    }

}
