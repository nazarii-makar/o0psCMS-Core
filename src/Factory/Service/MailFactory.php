<?php

namespace o0psCore\Factory\Service;

use Interop\Container\ContainerInterface;
use o0psCore\Options\ModuleOptions;
use Zend\Mail\Message;
use Zend\Mail\Transport\File as FileTransport;
use Zend\Mail\Transport\FileOptions;
use Zend\Math\Rand;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MailFactory
 * @package o0psCore\Factory\Service
 */
class MailFactory implements FactoryInterface
{
    /**
     * @var \Zend\Mail\Transport\File
     */
    protected $mailTransport;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return $this
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->setOptions($container->get('o0psCore_module_options'));

        return $this;
    }

    /**
     * @param ServiceLocatorInterface $container
     * @param null                    $name
     * @param null                    $requestedName
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }

    /**
     * @param $to
     * @param $subject
     * @param $messageText
     *
     * @return bool
     */
    public function sendEmail($to, $subject, $messageText)
    {
        $transport     = $this->getMailTransport();
        $message       = new Message();
        $moduleOptions = $this->getOptions();
        try {
            $message->addTo($to)
                    ->addFrom($moduleOptions->getSenderEmailAddress(), $moduleOptions->getSenderEmailName())
                    ->setSubject($subject)
                    ->setBody($messageText);

            if ($moduleOptions->isSaveMail()) {
                $options = new FileOptions([
                    'path'     => $moduleOptions->getMailPath(),
                    'callback' => function (FileTransport $transport) {
                        return sprintf(
                            'Message_%f.txt',
                            microtime(true)
                        );
                    },
                ]);
                $transport->setOptions($options);
            }
            $transport->send($message);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * @return \Zend\Mail\Transport\File
     */
    public function getMailTransport()
    {
        if (null === $this->mailTransport) {
            $this->mailTransport = new FileTransport();
        }

        return $this->mailTransport;
    }

}