# Simple filter list Entity

Author: Nicolas PIN <pin.nicolas@free.fr>

It's my first custom FormType.

That FormType has been created for a project who had need a filter for filter the list of elements the most simply possible.

I have hope it will be useful for you.

Thank you.


## Configuration

### On the formType

```
class ByBeautifulFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstField', TextType::class)
        // ....
        ->add('myFilterField', SimpleFilterType::class, array(
                'class' => 'Your\Entity\Class',
                'choice_label' => 'YourProprietyToDisplay',
                'placeholder' => 'The text for the first option',
                'label' => 'The label select',
                'popover_title' => 'The title of helper popover',
                'popover_content' => 'The text of helper popover'
        ))
        // ....
        ;
    }
}
```

### On the twig template (in Javascript script)

```
{{ form_javascript(form.myFilterField) }}
``` 