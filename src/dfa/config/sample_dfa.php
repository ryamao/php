<?php

const SAMPLE_NUM_STATES = 4;

const SAMPLE_NUM_SYMBOLS = 2;

const SAMPLE_TRANSITIONS = [
    0 => [
        0 => 2,
        1 => 1
    ],
    1 => [
        0 => 3,
        1 => 0
    ],
    2 => [
        0 => 0,
        1 => 3
    ],
    3 => [
        0 => 1,
        1 => 2
    ]
];

const SAMPLE_START_STATE = 0;

const SAMPLE_ACCEPT_STATES = [0];

const SAMPLE_DFA = [
    'num_states' => SAMPLE_NUM_STATES,
    'num_symbols' => SAMPLE_NUM_SYMBOLS,
    'transitions' => SAMPLE_TRANSITIONS,
    'start_state' => SAMPLE_START_STATE,
    'accept_states' => SAMPLE_ACCEPT_STATES
];
