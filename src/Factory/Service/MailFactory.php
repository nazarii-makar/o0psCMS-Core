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

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->setOptions($container->get('o0psCore_module_options'));

        return $this;
    }

    public function createService(ServiceLocatorInterface $container, $name = null, $requestedName = null)
    {
        return $this($container, $requestedName, []);
    }

    public function sendEmail($to, $subject, $messageText)
    {
        $transport = $this->getMailTransport();
        $message = new Message();
        $moduleOptions = $this->getOptions();
        try {
        $message->addTo($to)
            ->addFrom($moduleOptions->getSenderEmailAddress(), $moduleOptions->getSenderEmailName())
            ->setSubject($subject)
            ->setBody($messageText);

        if ($moduleOptions->isSaveMail()) {
            $options = new FileOptions([
                'path' => $moduleOptions->getMailPath(),
                'callback' => function (FileTransport $transport) {
                    return sprintf(
                        'Message_%f_%s.txt',
                        microtime(true),
                        Rand::getString(8)
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
        if (null === $this->mailTransport)
            $this->mailTransport = new FileTransport();

        return $this->mailTransport;
    }

}