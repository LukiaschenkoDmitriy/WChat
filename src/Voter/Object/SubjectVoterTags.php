<?php

namespace App\Voter\Object;

class SubjectVoterTags {
    public function __construct(
        public string $COLLECTION,
        public string $GET,
        public string $POST,
        public string $PATCH,
        public string $DELETE
    ) { }
}