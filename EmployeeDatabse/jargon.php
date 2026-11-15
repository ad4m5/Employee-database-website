<?php
// Corporate Jargon Mode
// Because "I write code" has never once gotten anybody past the ATS.

// Handles the toggle link before any HTML is printed, then redirects back to
// the same page so the query string does not stick around in the address bar.
if (isset($_GET['jargon'])) {
    $on = $_GET['jargon'] === '1';
    setcookie('jargon', $on ? '1' : '', [
        'expires'  => $on ? 0 : time() - 3600,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    $query = $_GET;
    unset($query['jargon']);

    $target = strtok($_SERVER['REQUEST_URI'] ?? 'index.php', '?');
    if ($query) {
        $target .= '?' . http_build_query($query);
    }

    header('Location: ' . $target);
    exit();
}

// Is the user currently speaking fluent LinkedIn?
function jargon_on() {
    return ($_COOKIE['jargon'] ?? '') === '1';
}

// Same page, toggle filpped, other query params (search, id) preserved.
function jargon_toggle_url() {
    $query = $_GET;
    $query['jargon'] = jargon_on() ? '0' : '1';
    return '?' . http_build_query($query);
}

function jargon_label() {
    return jargon_on() ? 'Corporate Mode: ON' : 'Corporate Mode: OFF';
}

// Glossary. Keys must be lowercase; longest phrases win (see below).
function jargon_glossary() {
    return [
        'update my cv'            => 'optimize my personal brand',
        'update your cv'          => 'optimize your personal brand',
        'registration complete'   => 'onboarding complete',
        'registration failed'     => 'onboarding friction detected',
        'is already registered'   => 'has already joined the journey',
        'login successful'        => 'authentication successful',
        'wrong password'          => 'credential mismatch',
        'user not found'          => 'human not found in ecosystem',
        'cv not found'            => 'talent profile not found',
        'cv updated'              => 'personal brand optimized',
        'failed to update'        => 'optimization blocked pending alignment',
        'all cvs'                 => 'Unified Talent Repository',
        'search by name or skill' => 'query by human capital or competency',
        'years of experience'     => 'years of value delivery',
        'passionate about'        => 'hyper-focused on',
        'clean code'              => 'code hygiene',
        'web applications'        => 'mission-critical web solutions',
        'user-centric design'     => 'human-centered innovation',
        'full-stack'              => 'omni-stack',
        'full stack'              => 'omni-stack',
        'front-end'               => 'experience-layer',
        'backend'                 => 'revenue-critical backend',
        'frontend'                => 'experience-layer',
        'user-centric'            => 'human-centered',
        'databases'               => 'data ecosystems',
        'database'                => 'data ecosystem',
        'developer'               => 'value creator',
        'engineer'                => 'growth engineer',
        'experience'              => 'synergistic exposure',
        'optimization'            => 'performance enablement',
        'scalability'             => 'hyper-growth readiness',
        'security'                => 'zero-trust posture',
        'collaborate'             => 'synergize',
        'certificate'             => 'Micro-Credential',
        'university'              => 'Innovation Campus',
        'building'                => 'architecting',
        'software'                => 'solutions',
        'projects'                => 'initiatives',
        'project'                 => 'initiative',
        'problems'                => 'opportunities',
        'problem'                 => 'opportunity',
        'skills'                  => 'Core Competencies',
        'skill'                   => 'competency',
        'learning'                => 'upskilling',
        'profile'                 => 'Personal Value Proposition',
        'education'               => 'Credentialing Journey',
        'links'                   => 'Digital Presence',
        'email'                   => 'Preferred Comms Channel',
        'password'                => 'Access Credential',
        'register'                => 'onboard',
        'logout'                  => 'disengage',
        'login'                   => 'authenticate',
        'search'                  => 'query',
        'team'                    => 'cross-functional pod',
        'back'                    => 'return to dashboard',
    ];
}

// Rewrites plain English into stakeholder-ready English.
function jargonify($text) {
    $text = (string)$text;
    if (!jargon_on() || $text === '') {
        return $text;
    }

    static $map = null;
    static $pattern = null;

    if ($map === null) {
        $map = jargon_glossary();
        // Longest phrase first so "years of experience" beats "experience".
        uksort($map, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        $phrases = array_map(function ($phrase) {
            return preg_quote($phrase, '/');
        }, array_keys($map));

        $pattern = '/\b(' . implode('|', $phrases) . ')\b/iu';
    }

    return preg_replace_callback($pattern, function ($match) use ($map) {
        $replacement = $map[strtolower($match[1])] ?? null;
        if ($replacement === null) {
            return $match[1];
        }
        // Keep the original capitalisation so sentences still start properly.
        if (preg_match('/^\p{Lu}/u', $match[1])) {
            $replacement = mb_strtoupper(mb_substr($replacement, 0, 1)) . mb_substr($replacement, 1);
        }
        return $replacement;
    }, $text);
}
