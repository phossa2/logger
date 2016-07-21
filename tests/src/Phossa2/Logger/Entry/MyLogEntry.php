<?php

namespace Phossa2\Logger\Entry;

class MyLogEntry extends LogEntry
{
    /**
     * {@inheritDoc}
     * @see LogEntry::getFormatted()
     */
    public function getFormatted()
    {
        return 'MyLogEntry: ' . $this->formatted;
    }
}
