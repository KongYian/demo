<?php
/**
 * Definition for a singly-linked list.
 * class ListNode {
 *     public $val = 0;
 *     public $next = null;
 *     function __construct($val) { $this->val = $val; }
 * }
 */

/**
 * Class Solution
 *
 * 输入：(2 -> 4 -> 3) + (5 -> 6 -> 4)
 * 输出：7 -> 0 -> 8
 * 原因：342 + 465 = 807
 */
class Solution {

    /**
     * @param ListNode $l1
     * @param ListNode $l2
     * @return ListNode
     */
    function addTwoNumbers($l1, $l2) {
        $l3 = '';
        foreach ($l1 as $k => $l1v) {
            if(isset($l2[$k])) {
                $l2v = $l2[$k];
                $s = $l1[$k] + $l2v;
                if($s >= 10) {
                    $yu = $s % 10;
                    if(isset($l1[$k+1])) {
                        $l1[$k+1] = $l1[$k+1] + 1;
                    }
                    $l3 .= $yu;
                } else {
                    $l3 .= $s;
                }
            } else {
                $l3 .= $l1[$k];
            }
        }
        return strrev($l3);
    }
}

class Solution2 {
    function addTwoNumbers($l1, $l2) {

        $carry = 0;
        $dummy = $curr = new ListNode(0);

        while($l1 || $l2) {

            $val1 = $l1 ? $l1->val : 0;
            $val2 = $l2 ? $l2->val : 0;

            $sum   = $val1 + $val2 + $carry;
            $carry = intval($sum / 10);

            $curr->next = new ListNode($sum % 10);
            $curr = $curr->next;

            if ($l1) $l1 = $l1->next;
            if ($l2) $l2 = $l2->next;
        }

        if ($carry) $curr->next = new ListNode($carry);

        return $dummy->next;
    }
}

$l1 = [2,4,3];
$l2 = [5,6,4];
echo (new Solution())->addTwoNumbers($l1,$l2);