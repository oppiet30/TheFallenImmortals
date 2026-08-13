<?php

use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    public function testFormatBloodZero(): void
    {
        $this->assertSame("0 gal 0 pt 0 oz.", formatBlood(0));
    }

    public function testFormatBloodUnderOnePint(): void
    {
        $this->assertSame("0 gal 0 pt 10 oz.", formatBlood(10));
    }

    public function testFormatBloodExactlyOnePint(): void
    {
        $this->assertSame("0 gal 1 pt 0 oz.", formatBlood(16));
    }

    public function testFormatBloodExactlyOneGallon(): void
    {
        $this->assertSame("1 gal 0 pt 0 oz.", formatBlood(128));
    }

    public function testFormatBloodMixedGallonsPintsOunces(): void
    {
        // 200 oz = 1 gal (128) + 72 oz remainder = 4 pt (64) + 8 oz
        $this->assertSame("1 gal 4 pt 8 oz.", formatBlood(200));
    }

    public function testFormatBloodTruncatesFractionalOunces(): void
    {
        $this->assertSame("0 gal 0 pt 10 oz.", formatBlood(10.9));
    }

    public function testFormatBloodFormatsLargeNumbersWithCommas(): void
    {
        // 200000 oz = 1562 gal remainder 64 oz = 4 pt 0 oz
        $this->assertSame("1,562 gal 4 pt 0 oz.", formatBlood(200000));
    }

    public function testCollidesWithBoxReturnsFalseForEmptyBoxes(): void
    {
        $this->assertFalse(collidesWithBox([0, 0], []));
    }

    public function testCollidesWithBoxDetectsPointInsideBox(): void
    {
        $boxes = [[-16, 16, -16, 16]];
        $this->assertTrue(collidesWithBox([0, 0], $boxes));
    }

    public function testCollidesWithBoxDetectsPointOutsideAllBoxes(): void
    {
        $boxes = [[-16, 16, -16, 16]];
        $this->assertFalse(collidesWithBox([100, 100], $boxes));
    }

    public function testCollidesWithBoxTreatsBoundaryAsInside(): void
    {
        // box is [xmin, xmax, ymin, ymax]; point sits exactly on xmax/ymax
        $boxes = [[-16, 16, -16, 16]];
        $this->assertTrue(collidesWithBox([16, 16], $boxes));
    }

    public function testCollidesWithBoxChecksAllBoxesUntilAMatch(): void
    {
        $boxes = [
            [-100, -84, -100, -84],
            [50, 82, 50, 82],
        ];
        $this->assertTrue(collidesWithBox([66, 66], $boxes));
    }

    public function testCarriageConvertsNewlinesToBrTokens(): void
    {
        $this->assertSame("line1[br]line2[br]line3", carriage("line1\r\nline2\nline3"));
    }

    public function testSmartcodeEscapesHtmlAndAppliesBbcode(): void
    {
        $result = smartcode("<script>[b]bold[/b]");
        $this->assertSame("&lt;script&gt;<strong>bold</strong>", $result);
    }
}
