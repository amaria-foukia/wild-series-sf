<?php

namespace App\Service;

use App\Entity\Program;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function slugifyName(Program $program)
    {
        $slug = $this->slugger->slug($program->getTitle())->toString();
        $program->setSlug($slug);
    }
}
