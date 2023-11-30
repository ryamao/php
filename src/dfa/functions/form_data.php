<?php

declare(strict_types=1);

require_once('config/sample_dfa.php');

function form_data_get_dfa_spec(array $form_data): array
{
    if (
        !array_key_exists('num-states', $form_data) &&
        !array_key_exists('num-symbol', $form_data) &&
        !array_key_exists('transitions', $form_data) &&
        !array_key_exists('start-state', $form_data) &&
        !array_key_exists('accept-states', $form_data)
    ) {
        return ['ok', SAMPLE_DFA];
    }

    if (
        !array_key_exists('num-states', $form_data) ||
        !array_key_exists('num-symbols', $form_data) ||
        !array_key_exists('transitions', $form_data) ||
        !array_key_exists('start-state', $form_data) ||
        !array_key_exists('accept-states', $form_data)
    ) {
        return ['error', null];
    }

    $num_states_str = $form_data['num-states'];
    if (!ctype_digit($num_states_str)) {
        return ['error', null];
    }
    $num_states = intval($num_states_str);
    if ($num_states <= 0 || 10 < $num_states) {
        return ['error', null];
    }

    $num_symbols_str = $form_data['num-symbols'];
    if (!ctype_digit($num_symbols_str)) {
        return ['error', null];
    }
    $num_symbols = intval($num_symbols_str);
    if ($num_symbols <= 0 || 10 < $num_symbols) {
        return ['error', null];
    }

    $transitions_str = $form_data['transitions'];
    $transitions = [];
    if (strlen($transitions_str) !== $num_states * $num_symbols) {
        return ['error', null];
    }
    foreach (str_split($transitions_str, $num_symbols) as $str) {
        $symbol_state_map = [];
        foreach (str_split($str) as $state_str) {
            if (!ctype_digit($state_str)) {
                return ['error', null];
            }
            $state = intval($state_str);
            if ($state >= $num_states) {
                return ['error', null];
            }
            $symbol_state_map[] = $state;
        }
        $transitions[] = $symbol_state_map;
    }

    $start_state_str = $form_data['start-state'];
    if (
        strlen($start_state_str) !== 1 ||
        !ctype_digit($start_state_str)
    ) {
        return ['error', null];
    }
    $start_state = intval($start_state_str);
    if ($start_state >= $num_states) {
        return ['error', null];
    }

    $accept_states_str = $form_data['accept-states'];
    $accept_states = [];
    foreach (str_split($accept_states_str) as $state_str) {
        if (!ctype_digit($state_str)) {
            return ['error', null];
        }
        $state = intval($state_str);
        if ($state >= $num_states) {
            return ['error', null];
        }
        $accept_states[] = $state;
    }
    $accept_states = array_unique($accept_states);

    $dfa = compact(
        'num_states',
        'num_symbols',
        'transitions',
        'start_state',
        'accept_states'
    );
    return ['ok', $dfa];
}

function form_data_has_error(array $form_data): bool
{
    return array_key_exists('error', $form_data);
}

function form_data_get_input_symbols(int $num_symbols, array $form_data): array
{
    if (!array_key_exists('input-symbols', $form_data)) {
        return ['none', []];
    }
    $input_symbols_str = $form_data['input-symbols'];
    if (strlen($input_symbols_str) === 0) {
        return ['none', []];
    }
    $input_symbols = [];
    foreach (str_split($input_symbols_str) as $symbol_str) {
        if (!ctype_digit($symbol_str)) {
            return ['error', null];
        }
        $symbol = intval($symbol_str);
        if ($symbol >= $num_symbols) {
            return ['error', null];
        }
        $input_symbols[] = $symbol;
    }
    return ['ok', $input_symbols];
}

function form_data_get_next_symbol(int $num_symbols, array $form_data): array
{
    if (!array_key_exists('next-symbol', $form_data)) {
        return ['none', null];
    }
    $next_symbol_str = $form_data['next-symbol'];
    if (strlen($next_symbol_str) !== 1 || !ctype_digit($next_symbol_str)) {
        return ['error', null];
    }
    $next_symbol = intval($next_symbol_str);
    if ($next_symbol >= $num_symbols) {
        return ['error', null];
    }
    return ['ok', $next_symbol];
}

function form_data_is_clear_button_pressed(array $form_data): bool
{
    return
        array_key_exists('control', $form_data) &&
        $form_data['control'] === 'clear';
}

function form_data_is_backspace_button_pressed(array $form_data): bool
{
    return
        array_key_exists('control', $form_data) &&
        $form_data['control'] === 'backspace';
}

function form_data_is_save_button_pressed(array $form_data): bool
{
    return array_key_exists('save', $form_data);
}

function form_data_is_symbol_addition_button_pressed(array $form_data): bool
{
    return array_key_exists('add-symbol', $form_data);
}

function form_data_is_state_addition_button_pressed(array $form_data): bool
{
    return array_key_exists('add-state', $form_data);
}

function form_data_is_symbol_removal_button_pressed(array $form_data): bool
{
    return array_key_exists('remove-symbol', $form_data);
}

function form_data_is_state_removal_button_pressed(array $form_data): bool
{
    return array_key_exists('remove-state', $form_data);
}
