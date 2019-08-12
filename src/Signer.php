<?php

namespace Assert6\Mtop;

class Signer
{
    protected function uRight($a, $n)
    {
        if ($n == 32 || $n == 0) {
            return $a >= 0 ? $a : 4294967296 + $a;
        }
        $c = 2147483647 >> ($n - 1);
        return $c & ($a >> $n);
    }

    protected function b($a, $b)
    {
        return $a << $b | $this->uRight($a, 32 - $b);
    }

    protected function c($a, $b)
    {
        $e = 2147483648 & $a;
        $f = 2147483648 & $b;
        $c = 1073741824 & $a;
        $d = 1073741824 & $b;
        $g = (1073741823 & $a) + (1073741823 & $b);
        if ($c & $d) {
            return 2147483648 ^ $g ^ $e ^ $f;
        } elseif ($c | $d) {
            if (1073741824 & $g) {
                return 3221225472 ^ $g ^ $e ^ $f;
            } else {
                return 1073741824 ^ $g ^ $e ^ $f;
            }
        } else {
            return $g ^ $e ^ $f;
        }
    }

    protected function d($a, $b, $c)
    {
        return $a & $b | ~$a & $c;
    }

    protected function e($a, $b, $c)
    {
        return $a & $c | $b & ~$c;
    }

    protected function f($a, $b, $c)
    {
        return $a ^ $b ^ $c;
    }

    protected function g($a, $b, $c)
    {
        return $b ^ ($a | ~$c);
    }

    protected function h($a, $e, $f, $g, $h, $i, $j)
    {
        $a = $this->c($a, $this->c($this->c($this->d($e, $f, $g), $h), $j));
        return $this->c($this->b($a, $i), $e);
    }

    protected function i($a, $d, $f, $g, $h, $i, $j)
    {
        $a = $this->c($a, $this->c($this->c($this->e($d, $f, $g), $h), $j));
        return $this->c($this->b($a, $i), $d);
    }

    protected function j($a, $d, $e, $g, $h, $i, $j)
    {
        $a = $this->c($a, $this->c($this->c($this->f($d, $e, $g), $h), $j));
        return $this->c($this->b($a, $i), $d);
    }

    protected function k($a, $d, $e, $f, $h, $i, $j)
    {
        $a = $this->c($a, $this->c($this->c($this->g($d, $e, $f), $h), $j));
        return $this->c($this->b($a, $i), $d);
    }

    protected function l($a)
    {
        $c = strlen($a);
        $d = $c + 8;
        $e = ($d - $d % 64) / 64;
        $f = 16 * ($e + 1);
        $g = [];
        for ($i = 0; $c > $i; $i++) {
            $b = ($i - $i % 4) / 4;
            $h = $i % 4 * 8;
            $g[$b] = ($g[$b] ?? 0) | ord($a[$i]) << $h;
        }
        $b = ($i - $i % 4) / 4;
        $h = $i % 4 * 8;
        $g[$b] = $g[$b] | 128 << $h;
        $g[$f - 2] = $c << 3;
        $g[$f - 1] = $this->uRight($c, 29);
        return $g;
    }

    protected function m($a)
    {
        $d = "";
        for ($c = 0; 3 >= $c; $c++) {
            $b = $this->uRight($a, 8 * $c) & 255;
            $e = "0" . dechex($b);
            $d .= substr($e, strlen($e) - 2, 2);
        }
        return $d;
    }

    protected function n($a)
    {
        $a = preg_replace("/\r\n/", "\n", $a);
        $b = '';
        for ($c = 0; $c < strlen($a); $c++) {
            $d = ord($a[$c]);
            128 > $d ? $b .= $this->fromCharCode($d) : $d > 127 && 2048 > $d ? ($b .= $this->fromCharCode($d >> 6 | 192) &&
                $b .= $this->fromCharCode(63 & $d | 128)) : ($b .= $this->fromCharCode($d >> 12 | 224) &&
                $b .= $this->fromCharCode($d >> 6 & 63 | 128) &&
                    $b .= $this->fromCharCode(63 & $d | 128));
        }
        return $b;
    }

    protected function fromCharCode($codes)
    {
        if (is_scalar($codes)) $codes = func_get_args();
        $str = '';
        foreach ($codes as $code)
            $str .= html_entity_decode('&#' . $code . ';', ENT_NOQUOTES, 'UTF-8');
        return $str;
    }

    public function sign($a)
    {
        $y = 7;
        $z = 12;
        $A = 17;
        $B = 22;
        $C = 5;
        $D = 9;
        $E = 14;
        $F = 20;
        $G = 4;
        $H = 11;
        $I = 16;
        $J = 23;
        $K = 6;
        $L = 10;
        $M = 15;
        $N = 21;
//        $a = $this->n($a);
        $x = $this->l($a);
        $t = 1732584193;
        $u = 4023233417;
        $v = 2562383102;
        $w = 271733878;
        for ($o = 0; $o < count($x); $o += 16) {
            $p = $t;
            $q = $u;
            $r = $v;
            $s = $w;
            $t = $this->h($t, $u, $v, $w, $x[$o + 0] ?? 0, $y, 3614090360);
            $w = $this->h($w, $t, $u, $v, $x[$o + 1] ?? 0, $z, 3905402710);
            $v = $this->h($v, $w, $t, $u, $x[$o + 2] ?? 0, $A, 606105819);
            $u = $this->h($u, $v, $w, $t, $x[$o + 3] ?? 0, $B, 3250441966);
            $t = $this->h($t, $u, $v, $w, $x[$o + 4] ?? 0, $y, 4118548399);
            $w = $this->h($w, $t, $u, $v, $x[$o + 5] ?? 0, $z, 1200080426);
            $v = $this->h($v, $w, $t, $u, $x[$o + 6] ?? 0, $A, 2821735955);
            $u = $this->h($u, $v, $w, $t, $x[$o + 7] ?? 0, $B, 4249261313);
            $t = $this->h($t, $u, $v, $w, $x[$o + 8] ?? 0, $y, 1770035416);
            $w = $this->h($w, $t, $u, $v, $x[$o + 9] ?? 0, $z, 2336552879);
            $v = $this->h($v, $w, $t, $u, $x[$o + 10] ?? 0, $A, 4294925233);
            $u = $this->h($u, $v, $w, $t, $x[$o + 11] ?? 0, $B, 2304563134);
            $t = $this->h($t, $u, $v, $w, $x[$o + 12] ?? 0, $y, 1804603682);
            $w = $this->h($w, $t, $u, $v, $x[$o + 13] ?? 0, $z, 4254626195);
            $v = $this->h($v, $w, $t, $u, $x[$o + 14] ?? 0, $A, 2792965006);
            $u = $this->h($u, $v, $w, $t, $x[$o + 15] ?? 0, $B, 1236535329);
            $t = $this->i($t, $u, $v, $w, $x[$o + 1] ?? 0, $C, 4129170786);
            $w = $this->i($w, $t, $u, $v, $x[$o + 6] ?? 0, $D, 3225465664);
            $v = $this->i($v, $w, $t, $u, $x[$o + 11] ?? 0, $E, 643717713);
            $u = $this->i($u, $v, $w, $t, $x[$o + 0] ?? 0, $F, 3921069994);
            $t = $this->i($t, $u, $v, $w, $x[$o + 5] ?? 0, $C, 3593408605);
            $w = $this->i($w, $t, $u, $v, $x[$o + 10] ?? 0, $D, 38016083);
            $v = $this->i($v, $w, $t, $u, $x[$o + 15] ?? 0, $E, 3634488961);
            $u = $this->i($u, $v, $w, $t, $x[$o + 4] ?? 0, $F, 3889429448);
            $t = $this->i($t, $u, $v, $w, $x[$o + 9] ?? 0, $C, 568446438);
            $w = $this->i($w, $t, $u, $v, $x[$o + 14] ?? 0, $D, 3275163606);
            $v = $this->i($v, $w, $t, $u, $x[$o + 3] ?? 0, $E, 4107603335);
            $u = $this->i($u, $v, $w, $t, $x[$o + 8] ?? 0, $F, 1163531501);
            $t = $this->i($t, $u, $v, $w, $x[$o + 13] ?? 0, $C, 2850285829);
            $w = $this->i($w, $t, $u, $v, $x[$o + 2] ?? 0, $D, 4243563512);
            $v = $this->i($v, $w, $t, $u, $x[$o + 7] ?? 0, $E, 1735328473);
            $u = $this->i($u, $v, $w, $t, $x[$o + 12] ?? 0, $F, 2368359562);
            $t = $this->j($t, $u, $v, $w, $x[$o + 5] ?? 0, $G, 4294588738);
            $w = $this->j($w, $t, $u, $v, $x[$o + 8] ?? 0, $H, 2272392833);
            $v = $this->j($v, $w, $t, $u, $x[$o + 11] ?? 0, $I, 1839030562);
            $u = $this->j($u, $v, $w, $t, $x[$o + 14] ?? 0, $J, 4259657740);
            $t = $this->j($t, $u, $v, $w, $x[$o + 1] ?? 0, $G, 2763975236);
            $w = $this->j($w, $t, $u, $v, $x[$o + 4] ?? 0, $H, 1272893353);
            $v = $this->j($v, $w, $t, $u, $x[$o + 7] ?? 0, $I, 4139469664);
            $u = $this->j($u, $v, $w, $t, $x[$o + 10] ?? 0, $J, 3200236656);
            $t = $this->j($t, $u, $v, $w, $x[$o + 13] ?? 0, $G, 681279174);
            $w = $this->j($w, $t, $u, $v, $x[$o + 0] ?? 0, $H, 3936430074);
            $v = $this->j($v, $w, $t, $u, $x[$o + 3] ?? 0, $I, 3572445317);
            $u = $this->j($u, $v, $w, $t, $x[$o + 6] ?? 0, $J, 76029189);
            $t = $this->j($t, $u, $v, $w, $x[$o + 9] ?? 0, $G, 3654602809);
            $w = $this->j($w, $t, $u, $v, $x[$o + 12] ?? 0, $H, 3873151461);
            $v = $this->j($v, $w, $t, $u, $x[$o + 15] ?? 0, $I, 530742520);
            $u = $this->j($u, $v, $w, $t, $x[$o + 2] ?? 0, $J, 3299628645);
            $t = $this->k($t, $u, $v, $w, $x[$o + 0] ?? 0, $K, 4096336452);
            $w = $this->k($w, $t, $u, $v, $x[$o + 7] ?? 0, $L, 1126891415);
            $v = $this->k($v, $w, $t, $u, $x[$o + 14] ?? 0, $M, 2878612391);
            $u = $this->k($u, $v, $w, $t, $x[$o + 5] ?? 0, $N, 4237533241);
            $t = $this->k($t, $u, $v, $w, $x[$o + 12] ?? 0, $K, 1700485571);
            $w = $this->k($w, $t, $u, $v, $x[$o + 3] ?? 0, $L, 2399980690);
            $v = $this->k($v, $w, $t, $u, $x[$o + 10] ?? 0, $M, 4293915773);
            $u = $this->k($u, $v, $w, $t, $x[$o + 1] ?? 0, $N, 2240044497);
            $t = $this->k($t, $u, $v, $w, $x[$o + 8] ?? 0, $K, 1873313359);
            $w = $this->k($w, $t, $u, $v, $x[$o + 15] ?? 0, $L, 4264355552);
            $v = $this->k($v, $w, $t, $u, $x[$o + 6] ?? 0, $M, 2734768916);
            $u = $this->k($u, $v, $w, $t, $x[$o + 13] ?? 0, $N, 1309151649);
            $t = $this->k($t, $u, $v, $w, $x[$o + 4] ?? 0, $K, 4149444226);
            $w = $this->k($w, $t, $u, $v, $x[$o + 11] ?? 0, $L, 3174756917);
            $v = $this->k($v, $w, $t, $u, $x[$o + 2] ?? 0, $M, 718787259);
            $u = $this->k($u, $v, $w, $t, $x[$o + 9] ?? 0, $N, 3951481745);
            $t = $this->c($t, $p);
            $u = $this->c($u, $q);
            $v = $this->c($v, $r);
            $w = $this->c($w, $s);
        }
        $O = $this->m($t) . $this->m($u) . $this->m($v) . $this->m($w);
        return strtolower($O);
    }
}