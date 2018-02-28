## Medium header
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quae contraria sunt his, malane? Inde sermone vario <i>[redacted]</i> illa a Dipylo stadia confecimus. Duo Reges: constructio interrete. Hinc ceteri particulas arripere conati suam quisque videro voluit afferre sententiam. In quibus doctissimi illi veteres inesse quiddam caeleste et divinum putaverunt.

Hoc non est positum in nostra actione. Audeo dicere, inquit. Bestiarum vero nullum iudicium puto. Materiam vero rerum et copiam apud hos exilem, apud illos uberrimam reperiemus. Et nemo nimium beatus est; Idemne potest esse dies saepius, qui semel fuit? Et certamen honestum et disputatio splendida! omnis est enim de virtutis dignitate contentio.

Quo modo autem optimum, si bonum praeterea nullum est? Parvi enim primo ortu sic iacent, tamquam omnino sine animo sint. Pauca mutat vel plura sane; Sed tempus est, si videtur, et recta quidem ad me. Illud non continuo, ut aeque incontentae. Iam in altera philosophiae parte. Minime vero istorum quidem, inquit. Virtutibus igitur rectissime mihi videris et ad consuetudinem nostrae orationis vitia posuisse contraria. Ego vero volo in virtute vim esse quam maximam; Quaerimus enim finem bonorum.



[Who are we?](http://movor.io/about)

>  This is a quote.
> It can span multiple lines!

![](https://www.espreso.rs/images_arhive/large/IMG_1456148194.jpg)

| Column 1 | Column 2 | Column 3 |
| -------- | -------- | -------- |
| John     | Doe      | Male     |
| Mary     | Smith    | Female   |
![](http://3.bp.blogspot.com/-jkfqjbrVXVY/VIqNrDb7bTI/AAAAAAAATJs/d0p9-4o2LXI/s1600/CASLAV%2BDJOKOVIC%2B4.jpg)
Or without aligning the columns...

| Column 1 | Column 2 | Column 3 |
| -------- | -------- | -------- |
| John | Doe | Male |
| Mary | Smith | Female |

`var example = "hello!";`

Or spanning multiple lines...

```js
/* ********************************************************************
 * Alphanum Array prototype version
 *  - Much faster than the sort() function version
 *  - Ability to specify case sensitivity at runtime is a bonus
 *
 */
Array.prototype.alphanumSort = function(caseInsensitive) {
  for (var z = 0, t; t = this[z]; z++) {
    this[z] = new Array();
    var x = 0, y = -1, n = 0, i, j;

    while (i = (j = t.charAt(x++)).charCodeAt(0)) {
      var m = (i == 46 || (i >=48 && i <= 57));
      if (m !== n) {
        this[z][++y] = "";
        n = m;
      }
      this[z][y] += j;
    }
  }

  this.sort(function(a, b) {
    for (var x = 0, aa, bb; (aa = a[x]) && (bb = b[x]); x++) {
      if (caseInsensitive) {
        aa = aa.toLowerCase();
        bb = bb.toLowerCase();
      }
      if (aa !== bb) {
        var c = Number(aa), d = Number(bb);
        if (c == aa && d == bb) {
          return c - d;
        } else return (aa > bb) ? 1 : -1;
      }
    }
    return a.length - b.length;
  });

  for (var z = 0; z < this.length; z++)
    this[z] = this[z].join("");
}
```

```php
<?php

namespace App\Lib\Mine;

use App\Lib\Coins\Coin;

class Mine
{
    protected $hashRate;
    protected $coin;

    public function __construct(Coin $coin, $hashRate)
    {
        $this->coin = $coin;
        $this->hashRate = $hashRate;
    }

    /**
     * Calculate profitability in all prices coin has
     *
     * @param int $period
     *
     * @return array
     */
    public function profitability($period = 1)
    {
        $userRatio = $this->hashRate / $this->coin->getNetworkHash();
        $blockPerMinute = 60.0 / $this->coin->getBlockTime();
        $rewardPerMinute = $this->coin->getBlockReward() * $blockPerMinute;
        $earningsInCoins = $userRatio * $rewardPerMinute * $period;

        $profitability = [];
        foreach ($this->coin->getPrice() as $symbol => $price) {
            $profitability[$symbol] = $price * $earningsInCoins;
        }

        return $profitability;
    }

    public function getHashRate()
    {
        return $this->hashRate;
    }

    public function getCoin()
    {
        return $this->coin;
    }
}

```
![](https://xdn.tf.rs/2018/02/21/pc-16-830x0.jpg)
