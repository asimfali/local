<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 14.11.2017
 * Time: 7:35
 */

namespace Custom;


use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

class FlashMsgr
{
    public $status = 'success';
    public $message = '';
    /**
     * @var FlashMessenger
     */
    public $fm;
    public $entities = array();
    public function __construct(FlashMessenger $flashMessenger)
    {
        $this->fm = $flashMessenger;
    }
    public function pr()
    {
        $header = '';
        $this->entities[] = '<ul>';
        switch ($this->status){
            case 'error':
                foreach ($this->fm->getErrorMessages() as $errorMessage) {
                    $header = "<div class='alert alert-dismissable alert-danger'>
                        <button class='close' type='button' data-dismiss='alert' aria-hidden='true'>x</button>";
                    $this->entities[] = '<li>'. $errorMessage . '</li>';
                }
                break;
            case 'success':
                foreach ($this->fm->getSuccessMessages() as $successMessage) {
                    $header = "<div class='alert alert-dismissable alert-success'>
                        <button class='close' type='button' data-dismiss='alert' aria-hidden='true'>x</button>";
                    $this->entities[] = '<li>'. $successMessage . '</li>';
                }
                break;
            case 'info':
                foreach ($this->fm->getInfoMessages() as $infoMessage) {
                    $header = "<div class='alert alert-dismissable alert-info'>
                        <button class='close' type='button' data-dismiss='alert' aria-hidden='true'>x</button>";
                    $this->entities[] = '<li>'. $infoMessage . '</li>';
                }
                break;
            case 'warning':
                foreach ($this->fm->getWarningMessages() as $warningMessage) {
                    $header = "<div class='alert alert-dismissable alert-warning'>
                        <button class='close' type='button' data-dismiss='alert' aria-hidden='true'>x</button>";
                    $this->entities[] = '<li>'. $warningMessage . '</li>';
                }
                break;
            default:
                return;
                break;
        }
        if (count($this->entities) == 1) return;
        $this->entities[] = '</ul>';
        $this->entities[] = "</div>";
        echo $header;
        foreach ($this->entities as $entity) {
            echo $entity;
        }
    }
    public function add($message)
    {
        switch ($this->status){
            case 'error':
                $this->fm->addErrorMessage($message);
                break;
            case 'info':
                $this->fm->addInfoMessage($message);
                break;
            case 'success':
                $this->fm->addSuccessMessage($message);
                break;
            case 'warning':
                $this->fm->addWarningMessage($message);
                break;
            default:
                $this->fm->addMessage($message);
                break;
        }
    }
}