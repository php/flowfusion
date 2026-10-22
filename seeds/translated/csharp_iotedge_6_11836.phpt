--TEST--
Translated seed (inferredbugs-csharp: iotedge/6)
--FILE--
<?php
class AuthAgentProtocolHead {
    private $host = null;
    private $running = false;

    public function startAsync() {
        if ($this->host !== null) {
            throw new Exception("Cannot start AuthAgentProtocolHead twice");
        }
        $this->host = (object)["started" => true, "stopped" => false];
        $this->running = true;
    }

    public function closeAsync($token = null) {
        $hostToStop = $this->host;
        $this->host = null;
        if ($hostToStop === null) {
            throw new Exception("Cannot stop AuthAgentProtocolHead when not running");
        }
        if (!empty($token) && isset($token["cancelled"]) && $token["cancelled"]) {
            return;
        }
        $hostToStop->stopped = true;
        $this->running = false;
    }

    public function dispose() {
        if ($this->host !== null) {
            $this->closeAsync(["cancelled" => false]);
        }
    }
}

$head = new AuthAgentProtocolHead();
$head->startAsync();
try { $head->startAsync(); } catch (Exception $e) {}
$head->closeAsync();
try { $head->closeAsync(); } catch (Exception $e) {}
$head->dispose();
?>
--EXPECT--
