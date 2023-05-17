<?php

namespace TextMod;

use stdClass;

/**
 * Class ModerationResult
 */
class ModerationResult
{
    private $data;
    public $spam;
    public $selfPromoting;
    public $hate;
    public $terrorism;
    public $extremism;
    public $pornographic;
    public $threatening;
    public $selfHarm;
    public $sexual;
    public $sexualMinors;
    public $violence;
    public $violenceGraphic;

    public function __construct(array $data)
    {
        $this->data = (object)$data;
        $this->spam = isset($this->data->spam) && $this->data->spam;
        $this->selfPromoting = isset($this->data->{'self-promoting'}) && $this->data->{'self-promoting'};
        $this->hate = isset($this->data->hate) && $this->data->hate;
        $this->terrorism = isset($this->data->terrorism) && $this->data->terrorism;
        $this->extremism = isset($this->data->extremism) && $this->data->extremism;
        $this->pornographic = isset($this->data->pornographic) && $this->data->pornographic;
        $this->threatening = isset($this->data->threatening) && $this->data->threatening;
        $this->selfHarm = isset($this->data->{'self-harm'}) && $this->data->{'self-harm'};
        $this->sexual = isset($this->data->sexual) && $this->data->sexual;
        $this->sexualMinors = isset($this->data->{'sexual/minors'}) && $this->data->{'sexual/minors'};
        $this->violence = isset($this->data->violence) && (bool)$this->data->violence;
        $this->violenceGraphic = isset($this->data->{'violence/graphic'}) && $this->data->{'violence/graphic'};
    }
}
