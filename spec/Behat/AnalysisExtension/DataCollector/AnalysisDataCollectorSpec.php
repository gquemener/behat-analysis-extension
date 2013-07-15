<?php

namespace spec\Behat\AnalysisExtension\DataCollector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AnalysisDataCollectorSpec extends ObjectBehavior
{
    function it_inherits_from_the_logger_data_collector()
    {
        $this->shouldHaveType('Behat\Behat\DataCollector\LoggerDataCollector');
    }

    /**
     * @param Behat\Behat\Event\StepEvent             $firstEvent
     * @param Behat\Behat\Event\StepEvent             $secondEvent
     * @param Behat\Behat\Event\StepEvent             $thirdEvent
     * @param Behat\Behat\Definition\Annotation\Given $firstDefinition
     * @param Behat\Behat\Definition\Annotation\When  $secondDefinition
     * @param Behat\Behat\Definition\Annotation\Then  $thirdDefinition
     */
    function it_increments_steps_usages_after_each_step(
        $firstEvent, $secondEvent, $thirdEvent,
        $firstDefinition, $secondDefinition, $thirdDefinition
    )
    {
        $firstEvent->getDefinition()->willReturn($firstDefinition);
        $firstEvent->getResult()->willReturn(\Behat\Behat\Event\StepEvent::SKIPPED);
        $firstDefinition->getRegex()->willReturn('foo');
        $this->afterStep($firstEvent);

        $secondEvent->getDefinition()->willReturn($secondDefinition);
        $secondEvent->getResult()->willReturn(\Behat\Behat\Event\StepEvent::SKIPPED);
        $secondDefinition->getRegex()->willReturn('bar');
        $this->afterStep($secondEvent);

        $thirdEvent->getDefinition()->willReturn($thirdDefinition);
        $thirdEvent->getResult()->willReturn(\Behat\Behat\Event\StepEvent::SKIPPED);
        $thirdDefinition->getRegex()->willReturn('foo');
        $this->afterStep($thirdEvent);

        $this->getStepsUsages()->shouldReturn(array(
            'foo' => 2,
            'bar' => 1,
        ));
    }

    /**
     * @param Behat\Behat\Event\StepEvent             $firstEvent
     * @param Behat\Behat\Event\StepEvent             $secondEvent
     * @param Behat\Behat\Event\StepEvent             $thirdEvent
     * @param Behat\Behat\Definition\Annotation\Given $firstDefinition
     * @param Behat\Behat\Definition\Annotation\When  $secondDefinition
     * @param Behat\Behat\Definition\Annotation\Then  $thirdDefinition
     * @param Behat\Behat\Event\SuiteEvent            $event
     */
    function it_computes_which_steps_have_more_than_80_percent_of_similarity_after_the_suite_has_been_run(
        $firstEvent, $secondEvent, $thirdEvent,
        $firstDefinition, $secondDefinition, $thirdDefinition,
        $event
    )
    {
        $firstEvent->getDefinition()->willReturn($firstDefinition);
        $firstEvent->getResult()->willReturn(\Behat\Behat\Event\StepEvent::SKIPPED);
        $firstDefinition->getRegex()->willReturn('aaaaaaaaaa');
        $this->afterStep($firstEvent);

        $secondEvent->getDefinition()->willReturn($secondDefinition);
        $secondEvent->getResult()->willReturn(\Behat\Behat\Event\StepEvent::SKIPPED);
        $secondDefinition->getRegex()->willReturn('aaaaaaaabb');
        $this->afterStep($secondEvent);

        $thirdEvent->getDefinition()->willReturn($thirdDefinition);
        $thirdEvent->getResult()->willReturn(\Behat\Behat\Event\StepEvent::SKIPPED);
        $thirdDefinition->getRegex()->willReturn('I am totally different from the other steps');
        $this->afterStep($thirdEvent);

        $this->afterSuite($event);

        $this->getMergeableSteps()->shouldReturn(array(
            array(
                'source'  => 'aaaaaaaaaa',
                'compare' => 'aaaaaaaabb',
            )
        ));
    }
}
