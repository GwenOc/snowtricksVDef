<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditAvatarType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	 $builder
	    	->add('avatar', FileType::class, [
	       		'label' => 'Ton avatar',               
	        	'mapped' => false,               
	        	'required' => false,                
	        	'constraints' => [
	            	new File([
	                	'maxSize' => '500k',
	                	'mimeTypes' => [
	                    	'image/jpeg',
	                    	'image/png',
	                    	'image/gif'],
	                	'mimeTypesMessage' => 'Seuls les format .jpg, .png et .gif sont acceptés',
	            	])
	       		],
	   	 	])
			;
	}
}