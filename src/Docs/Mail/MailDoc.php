<?php

namespace Docs\Docs\Mail;

use Docs\Docs\ClassDoc;

class MailDoc extends ClassDoc
{
    /**
     * Describe Model.
     *
     * @return array
     */
    public function describe()
    {
        return [
            $this->getSummary(),
            // $this->describeMail(),
        ];
    }

    public function describeMail()
    {
        //
    }
}
