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

    public static function checkVote($cookies, $hash)
    {
        $lastVoteTime = $cookies->get("lastVoteTime")->getValue();
        $voteDateTime = new DateTime($lastVoteTime);
        $tomorrowDateTime = new DateTime("tomorrow");
        $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
        if($diffDateTimeCookie->d == 0){
            if($lastVotes = Vote::find("voteHash='{$vote->voteHash}'")){
                $lastVote = $lastVotes->getLast();
                $voteDateTime = new DateTime($lastVote->votedAt);
                $diffDateTimeHash = $voteDateTime->diff($tomorrowDateTime);
                if($diffDateTimeHash->d == 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}
