<?php

namespace PingThis\Ping;

interface PingInterface
{
    /**
     * Returns a string describing the last error, ie. the reason of the last
     * false response of ping().
     */
    public function getLastError(): ?string;

    /**
     * Returns a wanted frequency for this ping. This frequency may not be
     * regular, particularly if some other pings are slow.
     */
    public function getPingFrequency(): int;

    /**
     * Returns the consecutive max number of attempts if the ping fails. Passed
     * this number, the registered alarm is triggered.
     */
    public function getMaxAttemptsBeforeAlarm(): int;

    /**
     * Returns a short but descriptive string which describes the test that is
     * going to be done.
     */
    public function getName(): string;

    /**
     * Indicates if this ping succeeded or failed by returning respectively true
     * or false. The ping is responsible for saving internal data in case of
     * error, in order to be able to give a descriptive message later for the
     * alarm.
     */
    public function ping(): bool;
}
