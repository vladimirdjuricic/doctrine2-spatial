<?php
/**
 * Copyright (C) 2020 Alexandre Tranchant
 * Copyright (C) 2015 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Spatial\Tests\PHP\Types\Geometry;

use CrEOF\Spatial\PHP\Types\Geometry\LineString;
use CrEOF\Spatial\PHP\Types\Geometry\MultiPolygon;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use CrEOF\Spatial\PHP\Types\Geometry\Polygon;
use PHPUnit\Framework\TestCase;

/**
 * Polygon object tests.
 *
 * @group php
 *
 * @internal
 * @coversDefaultClass
 */
class MultiPolygonTest extends TestCase
{
    /**
     * Test an empty polygon.
     */
    public function testEmptyMultiPolygon()
    {
        $multiPolygon = new MultiPolygon([]);

        $this->assertEmpty($multiPolygon->getPolygons());
    }

    /**
     * Test to convert multipolygon to Json.
     */
    public function testJson()
    {
        // phpcs:disable Generic.Files.LineLength.MaxExceeded
        $expected = '{"type":"MultiPolygon","coordinates":[[[[0,0],[10,0],[10,10],[0,10],[0,0]]],[[[5,5],[7,5],[7,7],[5,7],[5,5]]]]}';
        // phpcs:enable
        $polygons = [
            [
                [
                    [0, 0],
                    [10, 0],
                    [10, 10],
                    [0, 10],
                    [0, 0],
                ],
            ],
            [
                [
                    [5, 5],
                    [7, 5],
                    [7, 7],
                    [5, 7],
                    [5, 5],
                ],
            ],
        ];
        $multiPolygon = new MultiPolygon($polygons);

        $this->assertEquals($expected, $multiPolygon->toJson());
    }

    /**
     * Test to get last polygon from a multipolygon created from a lot objects.
     */
    public function testMultiPolygonFromObjectsGetLastPolygon()
    {
        $firstPolygon = new Polygon(
            [
                new LineString(
                    [
                        new Point(0, 0),
                        new Point(10, 0),
                        new Point(10, 10),
                        new Point(0, 10),
                        new Point(0, 0),
                    ]
                ),
            ]
        );
        $lastPolygon = new Polygon(
            [
                new LineString(
                    [
                        new Point(5, 5),
                        new Point(7, 5),
                        new Point(7, 7),
                        new Point(5, 7),
                        new Point(5, 5),
                    ]
                ),
            ]
        );
        $multiPolygon = new MultiPolygon([$firstPolygon, $lastPolygon]);

        $this->assertEquals($lastPolygon, $multiPolygon->getPolygon(-1));
    }

    /**
     * Test to get first polygon from a multipolygon created from a lot objects.
     */
    public function testMultiPolygonFromObjectsGetSinglePolygon()
    {
        $firstPolygon = new Polygon(
            [
                new LineString(
                    [
                        new Point(0, 0),
                        new Point(10, 0),
                        new Point(10, 10),
                        new Point(0, 10),
                        new Point(0, 0),
                    ]
                ),
            ]
        );
        $lastPolygon = new Polygon(
            [
                new LineString(
                    [
                        new Point(5, 5),
                        new Point(7, 5),
                        new Point(7, 7),
                        new Point(5, 7),
                        new Point(5, 5),
                    ]
                ),
            ]
        );
        $multiPolygon = new MultiPolygon([$firstPolygon, $lastPolygon]);

        $this->assertEquals($firstPolygon, $multiPolygon->getPolygon(0));
    }

    /**
     * Test getPolygons method.
     */
    public function testSolidMultiPolygonAddPolygon()
    {
        $expected = [
            new Polygon(
                [
                    new LineString(
                        [
                            new Point(0, 0),
                            new Point(10, 0),
                            new Point(10, 10),
                            new Point(0, 10),
                            new Point(0, 0),
                        ]
                    ),
                ]
            ),
            new Polygon(
                [
                    new LineString(
                        [
                            new Point(5, 5),
                            new Point(7, 5),
                            new Point(7, 7),
                            new Point(5, 7),
                            new Point(5, 5),
                        ]
                    ),
                ]
            ),
        ];

        $polygon = new Polygon(
            [
                new LineString(
                    [
                        new Point(0, 0),
                        new Point(10, 0),
                        new Point(10, 10),
                        new Point(0, 10),
                        new Point(0, 0),
                    ]
                ),
            ]
        );

        $multiPolygon = new MultiPolygon([$polygon]);

        $multiPolygon->addPolygon(
            [
                [
                    new Point(5, 5),
                    new Point(7, 5),
                    new Point(7, 7),
                    new Point(5, 7),
                    new Point(5, 5),
                ],
            ]
        );

        $this->assertEquals($expected, $multiPolygon->getPolygons());
    }

    /**
     * Test getPolygons method.
     */
    public function testSolidMultiPolygonFromArraysGetPolygons()
    {
        $expected = [
            new Polygon(
                [
                    new LineString(
                        [
                            new Point(0, 0),
                            new Point(10, 0),
                            new Point(10, 10),
                            new Point(0, 10),
                            new Point(0, 0),
                        ]
                    ),
                ]
            ),
            new Polygon(
                [
                    new LineString(
                        [
                            new Point(5, 5),
                            new Point(7, 5),
                            new Point(7, 7),
                            new Point(5, 7),
                            new Point(5, 5),
                        ]
                    ),
                ]
            ),
        ];

        $polygons = [
            [
                [
                    [0, 0],
                    [10, 0],
                    [10, 10],
                    [0, 10],
                    [0, 0],
                ],
            ],
            [
                [
                    [5, 5],
                    [7, 5],
                    [7, 7],
                    [5, 7],
                    [5, 5],
                ],
            ],
        ];

        $multiPolygon = new MultiPolygon($polygons);

        $this->assertEquals($expected, $multiPolygon->getPolygons());
    }

    /**
     * Test to convert multipolygon created from array to string.
     */
    public function testSolidMultiPolygonFromArraysToString()
    {
        $expected = '((0 0,10 0,10 10,0 10,0 0)),((5 5,7 5,7 7,5 7,5 5))';
        $polygons = [
            [
                [
                    [0, 0],
                    [10, 0],
                    [10, 10],
                    [0, 10],
                    [0, 0],
                ],
            ],
            [
                [
                    [5, 5],
                    [7, 5],
                    [7, 7],
                    [5, 7],
                    [5, 5],
                ],
            ],
        ];
        $multiPolygon = new MultiPolygon($polygons);
        $result = (string) $multiPolygon;

        $this->assertEquals($expected, $result);
    }

    /**
     * Test to convert multipolygon created from objects to array.
     */
    public function testSolidMultiPolygonFromObjectsToArray()
    {
        $expected = [
            [
                [
                    [0, 0],
                    [10, 0],
                    [10, 10],
                    [0, 10],
                    [0, 0],
                ],
            ],
            [
                [
                    [5, 5],
                    [7, 5],
                    [7, 7],
                    [5, 7],
                    [5, 5],
                ],
            ],
        ];

        $polygons = [
            new Polygon(
                [
                    new LineString(
                        [
                            new Point(0, 0),
                            new Point(10, 0),
                            new Point(10, 10),
                            new Point(0, 10),
                            new Point(0, 0),
                        ]
                    ),
                ]
            ),
            new Polygon(
                [
                    new LineString(
                        [
                            new Point(5, 5),
                            new Point(7, 5),
                            new Point(7, 7),
                            new Point(5, 7),
                            new Point(5, 5),
                        ]
                    ),
                ]
            ),
        ];

        $multiPolygon = new MultiPolygon($polygons);

        $this->assertEquals($expected, $multiPolygon->toArray());
    }
}
