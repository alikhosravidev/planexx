<?php

declare(strict_types=1);

// Simple network connectivity test
echo "🔍 Testing network connectivity...\n\n";

// Test basic internet connection
echo "1. Testing basic internet connection:\n";
exec("curl -s --connect-timeout 5 https://www.google.com > /dev/null 2>&1", $output, $return);
echo ($return === 0 ? "✅ Internet OK\n" : "❌ Internet failed\n");

// Test OpenAI API endpoint
echo "\n2. Testing OpenAI API endpoint:\n";
exec("curl -s --connect-timeout 10 -I https://api.openai.com/v1/models 2>&1 | head -1", $output, $return);
$result = implode("\n", $output);
if (strpos($result, '200') !== false) {
    echo "✅ OpenAI API reachable\n";
} elseif (strpos($result, 'timeout') !== false) {
    echo "❌ OpenAI API timeout\n";
} else {
    echo "⚠️  OpenAI API: {$result}\n";
}

// Test Stability AI endpoint
echo "\n3. Testing Stability AI endpoint:\n";
exec("curl -s --connect-timeout 10 -I https://api.stability.ai/v1/user/account 2>&1 | head -1", $output, $return);
$result = implode("\n", $output);
if (strpos($result, '200') !== false || strpos($result, '401') !== false) {
    echo "✅ Stability AI API reachable\n";
} elseif (strpos($result, 'timeout') !== false) {
    echo "❌ Stability AI API timeout\n";
} else {
    echo "⚠️  Stability AI API: {$result}\n";
}

// Test Gemini API endpoint
echo "\n4. Testing Gemini API endpoint:\n";
exec("curl -s --connect-timeout 10 -I https://generativelanguage.googleapis.com/v1beta/models 2>&1 | head -1", $output, $return);
$result = implode("\n", $output);
if (strpos($result, '200') !== false) {
    echo "✅ Gemini API reachable\n";
} elseif (strpos($result, 'timeout') !== false) {
    echo "❌ Gemini API timeout\n";
} else {
    echo "⚠️  Gemini API: {$result}\n";
}

echo "\n📋 Summary:\n";
echo "- If all APIs show timeout, there's a network/firewall issue\n";
echo "- If some work and some don't, check API keys and quotas\n";
echo "- For production, consider using multiple providers with fallback\n";
