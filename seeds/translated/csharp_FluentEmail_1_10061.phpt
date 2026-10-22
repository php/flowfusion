--TEST--
Translated seed (inferredbugs-csharp: FluentEmail/1)
--FILE--
<?php
$email = new class {
    public $message;
    public $error = null;
    public function __construct() {
        $this->message = new stdClass();
        $this->message->to = [];
        $this->message->from = null;
        $this->message->subject = '';
        $this->message->body = '';
        $this->message->isBodyHtml = true;
    }
    public function to($emailAddress, $name = '') {
        $this->message->to[] = [$emailAddress, $name];
        return $this;
    }
    public function from($emailAddress, $name = '') {
        $this->message->from = [$emailAddress, $name];
        return $this;
    }
    public function subject($subject) {
        $this->message->subject = $subject;
        return $this;
    }
    public function body($body) {
        $this->message->body = $body;
        return $this;
    }
    public function send() {
        try {
            if (!filter_var($this->message->from[0] ?? '', FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid sender');
            }
            foreach ($this->message->to as $t) {
                if (!filter_var($t[0], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Invalid recipient');
                }
            }
        } catch (Throwable $ex) {
            $this->error = $ex;
        }
        return $this;
    }
};

$email->to("user@example.com", "A \"quoted\" name")
      ->from("sender@example.com", "Sender")
      ->subject(str_repeat("x", 0))
      ->body("<b>html</b>")
      ->send();

if ($email->error !== null) {
    throw $email->error;
}
?>
--EXPECT--
