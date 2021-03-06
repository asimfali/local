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
     * @var FlashMessenger $fm
     */
    public $fm;
    public $entities = array();
    public function __construct(FlashMessenger $flashMessenger)
    {
        $this->fm = $flashMessenger;
    }

    public function setStatus()
    {
        if ($this->fm->hasSuccessMessages()) $this->status = 'success';
        else if ($this->fm->hasWarningMessages()) $this->status = 'warning';
        else if ($this->fm->hasInfoMessages()) $this->status = 'info';
        else if ($this->fm->hasErrorMessages()) $this->status = 'error';
    }
    public function pr()
    {
        $this->setStatus();
        $header = '';
        $this->entities[] = '<ul>';
        switch ($this->status){
            case 'error':
                foreach ($this->fm->getErrorMessages() as $errorMessage) {
                    $header = "<div class='alert alert-dismissable alert-danger' style='margin-top: 21px;'>
                        <button class='close' type='button' data-dismiss='alert' aria-hidden='true'>x</button>";
                    $this->entities[] = '<li>'. $errorMessage . '</li>';
                }
                break;
            case 'success':
                foreach ($this->fm->getSuccessMessages() as $successMessage) {
                    $header = "<div class='alert alert-dismissable alert-success' style='margin-top: 21px;'>
                        <button class='close' type='button' data-dismiss='alert' aria-hidden='true'>x</button>";
                    $this->entities[] = '<li>'. $successMessage . '</li>';
                }
                break;
            case 'info':
                foreach ($this->fm->getInfoMessages() as $infoMessage) {
                    $header = "<div class='alert alert-dismissable alert-info' style='margin-top: 21px;'>
                        <button class='close' type='button' data-dismiss='alert' aria-hidden='true'>x</button>";
                    $this->entities[] = '<li>'. $infoMessage . '</li>';
                }
                break;
            case 'warning':
                foreach ($this->fm->getWarningMessages() as $warningMessage) {
                    $header = "<div class='alert alert-dismissable alert-warning' style='margin-top: 21px;'>
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