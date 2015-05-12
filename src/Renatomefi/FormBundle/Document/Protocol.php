<?php

namespace Renatomefi\FormBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Renatomefi\UserBundle\Document\User;
use Renatomefi\FormBundle\Document\ProtocolUser as NonUser;

/**
 * Class Protocol
 * @package Renatomefi\FormBundle\Document
 * @ODM\Document
 */
class Protocol
{
    /**
     * Setting up ODM Document
     */
    public function __construct()
    {
        $this->setCreatedAt(new \MongoDate());
        $this->user = new ArrayCollection();
        $this->nonUser = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->file = new ArrayCollection();
        $this->fieldValues = new ArrayCollection();
        $this->publish = new ArrayCollection();
    }

    /**
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @ODM\EmbedMany(targetDocument="ProtocolPublish")
     */
    protected $publish;

    /**
     * @ODM\Date
     */
    protected $createdAt;

    /**
     * @ODM\Date
     */
    protected $firstSaveDate;

    /**
     * @ODM\Date
     */
    protected $lastSaveDate;

    /**
     * @ODM\ReferenceMany(targetDocument="Renatomefi\UserBundle\Document\User")
     * @var ArrayCollection
     */
    protected $user;

    /**
     * @ODM\ReferenceMany(targetDocument="ProtocolFile", mappedBy="protocol")
     * @var ArrayCollection
     */
    protected $file;

    /**
     * @ODM\EmbedMany(targetDocument="ProtocolUser")
     * @var ArrayCollection
     */
    protected $nonUser;

    /**
     * @ODM\EmbedMany(targetDocument="ProtocolComment")
     * @var ArrayCollection
     */
    protected $comment;

    /**
     * @ODM\String
     */
    protected $conclusion;

    /**
     * @ODM\ReferenceOne(targetDocument="Form")
     */
    protected $form;

    /**
     * @ODM\EmbedMany(targetDocument="ProtocolFieldValue")
     * @var ArrayCollection
     */
    protected $fieldValues;

    /**
     * Get id
     *
     * @return $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \MongoDate $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \MongoDate $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set firstSaveDate
     *
     * @param \MongoDate $firstSaveDate
     * @return self
     */
    public function setFirstSaveDate($firstSaveDate)
    {
        $this->firstSaveDate = $firstSaveDate;
        return $this;
    }

    /**
     * Get firstSaveDate
     *
     * @return \MongoDate $firstSaveDate
     */
    public function getFirstSaveDate()
    {
        return $this->firstSaveDate;
    }

    /**
     * Set lastSaveDate
     *
     * @param \MongoDate $lastSaveDate
     * @return self
     */
    public function setLastSaveDate($lastSaveDate)
    {
        $this->lastSaveDate = $lastSaveDate;
        return $this;
    }

    /**
     * Get lastSaveDate
     *
     * @return \MongoDate $lastSaveDate
     */
    public function getLastSaveDate()
    {
        return $this->lastSaveDate;
    }

    /**
     * Set form
     *
     * @param \Renatomefi\FormBundle\Document\Form $form
     * @return self
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Get form
     *
     * @return \Renatomefi\FormBundle\Document\Form $form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Add user
     *
     * @param \Renatomefi\UserBundle\Document\User $user
     */
    public function addUser(User $user)
    {
        $this->user[] = $user;
    }

    /**
     * Remove user
     *
     * @param \Renatomefi\UserBundle\Document\User $user
     */
    public function removeUser(User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Is this protocol locked?
     *
     * @return bool
     */
    public function isLocked()
    {
        return $this->publish[0]->getLocked();
    }

    /**
     * Set publish to the protocol
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolPublish $publish
     */
    public function setPublish(ProtocolPublish $publish)
    {
        $this->publish[] = $publish;
    }

    /**
     * Add nonUser
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolUser $nonUser
     */
    public function addNonUser(NonUser $nonUser)
    {
        $this->nonUser[] = $nonUser;
    }

    /**
     * Remove nonUser
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolUser $nonUser
     */
    public function removeNonUser(NonUser $nonUser)
    {
        $this->nonUser->removeElement($nonUser);
    }

    /**
     * Get nonUser
     *
     * @return \Doctrine\Common\Collections\Collection $nonUser
     */
    public function getNonUser()
    {
        return $this->nonUser;
    }

    /**
     * @param $userName
     * @return \Renatomefi\FormBundle\Document\ProtocolUser
     */
    public function getOneNonUser($userName)
    {
        foreach ($this->nonUser as $nonUser) {
            if ($nonUser->getUsername() === $userName) {
                return $nonUser;
            }
        }
        return null;
    }

    /**
     * @param $userName
     * @return \Renatomefi\UserBundle\Document\User
     */
    public function getOneUser($userName)
    {
        foreach ($this->user as $user) {
            if ($user->getUsernameCanonical() === $userName) {
                return $user;
            }
        }
        return null;
    }

    /**
     * Add comment
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolComment $comment
     */
    public function addComment(ProtocolComment $comment)
    {
        $this->comment[] = $comment;
    }

    /**
     * Remove comment
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolComment $comment
     */
    public function removeComment(ProtocolComment $comment)
    {
        $this->comment->removeElement($comment);
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Get comment by Id
     *
     * @param $commentId
     * @return \Renatomefi\FormBundle\Document\ProtocolComment
     */
    public function getOneComment($commentId)
    {
        foreach ($this->comment as $comment) {
            if ($comment->getId() === $commentId) {
                return $comment;
            }
        }
        return null;
    }

    /**
     * Set conclusion
     *
     * @param string $conclusion
     * @return self
     */
    public function setConclusion($conclusion)
    {
        $this->conclusion = $conclusion;
        return $this;
    }

    /**
     * Get conclusion
     *
     * @return string $conclusion
     */
    public function getConclusion()
    {
        return $this->conclusion;
    }

    /**
     * Add file
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolFile $file
     */
    public function addFile(ProtocolFile $file)
    {
        $this->file[] = $file;
    }

    /**
     * Remove file
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolFile $file
     */
    public function removeFile(ProtocolFile $file)
    {
        $this->file->removeElement($file);
    }

    /**
     * Get file
     *
     * @return \Doctrine\Common\Collections\Collection $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Add fieldValue
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolFieldValue $fieldValue
     */
    public function addFieldValue(ProtocolFieldValue $fieldValue)
    {
        $this->fieldValues[] = $fieldValue;
    }

    /**
     * Remove fieldValue
     *
     * @param \Renatomefi\FormBundle\Document\ProtocolFieldValue $fieldValue
     */
    public function removeFieldValue(ProtocolFieldValue $fieldValue)
    {
        $this->fieldValues->removeElement($fieldValue);
    }

    /**
     * Get fieldValues
     *
     * @return \Doctrine\Common\Collections\Collection $fieldValues
     */
    public function getFieldValues()
    {
        return $this->fieldValues;
    }

    /**
     * @param $id
     * @return \Renatomefi\FormBundle\Document\ProtocolFieldValue $fieldValue
     */
    public function getFieldValueByFieldId($id)
    {
        foreach ($this->fieldValues as $value) {
            if ($value->getField()->getId() === $id) {
                return $value;
            }
        }
        return null;
    }
}
