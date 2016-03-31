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
                if(isset($lastVoteTimeChild)){
                    $voteDateTime = new DateTime($lastVoteTimeChild);
                    $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                }else{
                    $diffDateTimeCookie = (new DateTime("now"))->diff(new DateTime("tomorrow + 1day"));;
                }
                break;
            case 2:
                if(isset($lastVoteTimeJunior)){
                    $voteDateTime = new DateTime($lastVoteTimeJunior);
                    $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                }else{
                    $diffDateTimeCookie = (new DateTime("now"))->diff(new DateTime("tomorrow + 1day"));
                }
                break;
            case 3:
                if(isset($lastVoteTimeTeen)){
                    $voteDateTime = new DateTime($lastVoteTimeTeen);
                    $diffDateTimeCookie = $voteDateTime->diff($tomorrowDateTime);
                }else{
                    $diffDateTimeCookie = (new DateTime("now"))->diff(new DateTime("tomorrow + 1day"));;
                }
                break;
        }
        if($diffDateTimeCookie->d == 0){
            return false;
        }else{
            if($lastVotes = Vote::find("voteHash='{$hash}'")){
                $lastVote = $lastVotes->getLast();
                $voteDateTime = new DateTime($lastVote->votedAt);
                $diffDateTimeHash = $voteDateTime->diff($tomorrowDateTime);
                if($diffDateTimeHash->d == 0){
                    $voteCount = 0;
                    foreach ($lastVotes as $tmpVote) {
                        $voteDateTime = new DateTime($tmpVote->votedAt);
                        $diffDateTime = $voteDateTime->diff($tomorrowDateTime);
                        if($diffDateTime->d == 0) $voteCount++;
                    }
                    if($voteCount >= 50){
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return true;
                }
            }else{
                return true;
            }
        }
    }
}
