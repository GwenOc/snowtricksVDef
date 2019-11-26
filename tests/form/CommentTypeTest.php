<?php

namespace App\tests\form;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Trick;
use App\Form\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function testForm()
    {
        $author = $this->createMock(User::class);
        $trick = $this->createMock(Trick::class);
        $formData = [
            'text' => 'America national sport is called baseballs. It very similar to our sport, shurik, where we take dogs, shoot them in a field, and then have a party.',
        ];

        $objectToCompare = new Comment();

        $objectToCompare->setAuthor($author)
                        ->setTrick($trick);
        $form = $this->factory->create(CommentType::class, $objectToCompare);
        $object = new Comment();



        $object->setText('America national sport is called baseballs. It very similar to our sport, shurik, where we take dogs, shoot them in a field, and then have a party.')
            ->setAuthor($author)
            ->setTrick($trick);

        $form->submit($formData);
        $this->assertTrue($form->isValid());
        $this->assertEquals($object, $objectToCompare);
        $this->assertInstanceOf(Comment::class, $form->getData());
        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}