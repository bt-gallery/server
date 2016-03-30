<?php

class Vote extends \Phalcon\Mvc\Model
{
    public function columnMap()
    {
        return array(
            'id_vote' => 'idVote',
            'vote_ip' => 'voteIp',
            'vote_agent' => 'voteAgent',
            'vote_group' => 'voteGroup',
            'voted_at' => 'votedAt',
            'competitive_work_id_competitive_work' => 'competitiveWorkIdCompetitiveWork',
            'vote_hash' => 'voteHash'
        );
    }

    public static function checkVote($cookies, $hash, $age)
    {
        $group = Participant::getGroupS($age);
        $tomorrowDateTime = new DateTime("tomorrow");
        if ($cookies->has("lastVoteTimeChild")) {
                $lastVoteTimeChild = $cookies->get("lastVoteTimeChild")->getValue();
            }
            if ($cookies->has("lastVoteTimeJunior")) {
                $lastVoteTimeJunior = $cookies->get("lastVoteTimeJunior")->getValue();
            }
            if ($cookies->has("lastVoteTimeTeen")) {
                $lastVoteTimeTeen = $cookies->get("lastVoteTimeTeen")->getValue();
            }
        switch ($group) {
            case 1:
                if($lastVoteTimeChild){
                    $voteDateTime = new DateTime($lastVoteTimeChild);
                    $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                }else{
                    $diffDateTimeCookie->d = 0;
                }
                break;
            case 2:
                if($lastVoteTimeJunior){
                    $voteDateTime = new DateTime($lastVoteTimeJunior);
                    $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                }else{
                    $diffDateTimeCookie->d = 0;
                }
                break;
            case 3:
                if($$lastVoteTimeTeen){
                    $voteDateTime = new DateTime($$lastVoteTimeTeen);
                    $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                }else{
                    $diffDateTimeCookie->d = 0;
                }
                break;
        }
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
