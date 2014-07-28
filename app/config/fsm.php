<?php

return array(
    'instant_order' => array(
        'class'       => 'Document',
        'states'      => array(
            'draft'    => array(
                'type'       => Finite\State\StateInterface::TYPE_INITIAL,
                'properties' => array('deletable' => true, 'editable' => true),
            ),
            'proposed' => array(
                'type'       => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'accepted' => array(
                'type'       => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array('printable' => true),
            )
        ),
        'transitions' => array(
            'propose' => array('from' => array('draft'), 'to' => 'proposed'),
            'accept'  => array('from' => array('proposed'), 'to' => 'accepted'),
            'reject'  => array('from' => array('proposed', 'accepted'), 'to' => 'draft'),
        ),
        'callbacks' => array(
            'before' => array(
                array(
                    'from' => 'draft',
                    'to' => 'proposed',
                    'do' => function(Finite\StatefulInterface $document, \Finite\Event\TransitionEvent $e) {
                            echo 'Applying transition '.$e->getTransition()->getName(), "\n";
                        }
                ),
                array(
                    'from' => 'proposed',
                    'do' => function() {
                            echo 'Applying transition from proposed state', "\n";
                        }
                )
            ),
            'after' => array(
                array(
                    'to' => array('accepted'), 'do' => function(){

                    }
                )
            )
        )
    )
);
