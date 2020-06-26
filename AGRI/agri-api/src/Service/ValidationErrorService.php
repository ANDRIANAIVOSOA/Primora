<?php
namespace App\Service;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\VisitorInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintViolation;

class ValidationErrorService implements SubscribingHandlerInterface
{
    private $translator;

    public static function getSubscribingMethods()
    {
        $methods = array();
        $methods[] = array(
            'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
            'type' => Form::class,
            'format' => 'json',
        );

        return $methods;
    }

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function serializeFormToJson(JsonSerializationVisitor $visitor, Form $form, array $type, Context $context = null)
    {
        $isRoot = null === $visitor->getRoot();
        $result = $this->adaptFormArray($this->convertFormToArray($visitor, $form), $context);

        if ($isRoot) {
            $visitor->setRoot($result);
        }

        return $result;

    }

    private function getErrorMessage(FormError $error)
    {
        if (null !== $error->getMessagePluralization()) {
            return $this->translator->transChoice($error->getMessageTemplate(), $error->getMessagePluralization(), $error->getMessageParameters(), 'validators');
        }

        return $this->translator->trans($error->getMessageTemplate(), $error->getMessageParameters(), 'validators');
    }

    private function getErrorPayloads(FormError $error)
    {
        /**
         * @var $cause ConstraintViolation
         */
        $cause = $error->getCause();

        if (!($cause instanceof ConstraintViolation) || !$cause->getConstraint() || empty($cause->getConstraint()->payload['error_code'])) {
            return null;
        }

        return $cause->getConstraint()->payload['error_code'];
    }

    private function convertFormToArray(VisitorInterface $visitor, Form $data)
    {
        $isRoot = null === $visitor->getRoot();

        $form = new \ArrayObject();
        $errorCodes = array();
        $errors = array();

        foreach ($data->getErrors() as $error) {
            $errors[] = $this->getErrorMessage($error);
        }

        foreach ($data->getErrors() as $error) {
            $errorCode = $this->getErrorPayloads($error);
            if (is_array($errorCode)) {
                $errorCodes = array_merge($errorCodes, array_values($errorCode));
            } elseif ($errorCode !== null) {
                $errorCodes[] = $errorCode;
            }
        }

        if ($errors) {
            $form['errors'] = $errors;
            if ($errorCodes) {
                $form['error_codes'] = array_unique($errorCodes);
            }
        }

        $children = array();
        foreach ($data->all() as $child) {
            if ($child instanceof Form) {
                $children[$child->getName()] = $this->convertFormToArray($visitor, $child);
            }
        }

        if ($children) {
            $form['children'] = $children;
        }

        if ($isRoot) {
            $visitor->setRoot($form);
        }

        return $form;
    }


    private function adaptFormArray(\ArrayObject $serializedForm, Context $context = null)
    {
        $statusCode = $this->getStatusCode($context);
        if (null !== $statusCode) {
            return [
                'code' => $statusCode,
                'message' => 'Erreur Validation',
                'errors' => $serializedForm,
            ];
        }

        return $serializedForm;
    }

    private function getStatusCode(Context $context = null)
    {
        if (null === $context) {
            return;
        }

        $statusCode = $context->attributes->get('status_code');
        if ($statusCode->isDefined()) {
            return $statusCode->get();
        }
    }
}