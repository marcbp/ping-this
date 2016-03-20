<?php

namespace MarcBP\PingThis;

use MarcBP\PingThis\Ping\PingInterface;
use MarcBP\PingThis\Alarm\AlarmInterface;

class Daemon
{
    protected $alarm;
    protected $pings = [];
    protected $lastCheck;
    protected $inErrorState;
    
    public function __construct()
    {
        $this->lastCheck = new \SplObjectStorage();
        $this->inErrorState = new \SplObjectStorage();
    }
    
    public function registerPing(PingInterface $ping)
    {
        $this->pings[] = $ping;
        $this->lastCheck[$ping] = 0;
    }
    
    public function registerAlarm(AlarmInterface $alarm)
    {
        $this->alarm = $alarm;
    }
    
    public function run()
    {
        while (1) {
            foreach ($this->pings as $ping) {
                if ((time() - $this->lastCheck[$ping]) >= $ping->getPingFrequency()) {
                    $this->lastCheck[$ping] = time();
                    
                    // This ping triggers an error
                    if (!$ping->ping()) {
                        if (!$this->inErrorState->contains($ping)) {
                            $this->inErrorState->attach($ping);
                            $this->alarm->start($ping);
                        }
                    }
                    
                    // This ping instance was in error state
                    elseif ($this->inErrorState->contains($ping)) {
                        $this->inErrorState->detach($ping);
                        $this->alarm->stop($ping);
                    }                    
                }
            }
            
            sleep(1);
        }
    }
}