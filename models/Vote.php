<?php

class Vote extends \Phalcon\Mvc\Model
{
    public function columnMap()
    {
        return array(
            'id_vote' => 'idVote',
            'vote_ip' => 'voteIp',
            'vote_agent' => 'voteAgent',
            'voted_at' => 'votedAt',
            'competitive_work_id_competitive_work' => 'competitiveWorkIdCompetitiveWork',
            'vote_hash' => 'voteHash'
        );
    }
}
